package main

import (
	"net/http"
	"log"
	"fmt"
	"encoding/json"
	"io/ioutil"
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
	"github.com/BurntSushi/toml"
	"path/filepath"
	"os"
)

type Config struct {
	Database struct {
		Host string
		Username string
		Password string
		Database string
		Port int
	}

	Api struct {
		Url string
	}
}

type DollywoodRide struct {
	RideId int
	RideName string
	OperationStatus string
	WaitTime int
	WaitTimeDisplay string
	WaitTimeDate int
	Error string
}

func main() {
	var config Config
	currentDir, err := filepath.Abs(filepath.Dir(os.Args[0]));
	configFile := fmt.Sprintf("%s/config.toml", currentDir)
	if _, err := toml.DecodeFile(configFile, &config); err != nil {
		log.Fatal(err)
	}
	db_host := config.Database.Host
	db_user := config.Database.Username
	db_pass := config.Database.Password
	db_name := config.Database.Database
	db_port := config.Database.Port
	waits_url := config.Api.Url

	db_dsn := fmt.Sprintf("%s:%s@tcp(%s:%d)/%s", db_user, db_pass, db_host, db_port, db_name)
	db, err := sql.Open("mysql", db_dsn)
	defer db.Close()

	if err != nil {
		log.Fatal(err)
	}

	response, err := http.Get(waits_url)
	defer response.Body.Close()

	if err != nil {
		log.Fatal(err)
	}

	fmt.Printf("Status code %d.\n", response.StatusCode)

	jsonDataFromHttp, err := ioutil.ReadAll(response.Body)
	if err != nil {
		log.Fatal(err)
	}

	var rides []DollywoodRide

	err = json.Unmarshal(jsonDataFromHttp, &rides)
	if err != nil {
		log.Fatal(err)
	}

	fmt.Printf("%d rides parsed.\n", len(rides))

	for _, ride := range rides {
		switch ride.OperationStatus {
		case "OPEN":
			fmt.Printf("%s has a %d minute wait.\n", ride.RideName, ride.WaitTime)
		case "CLOSED FOR THE DAY":
			fmt.Printf("%s is closed for the day.\n", ride.RideName)
		case "CLOSED FOR THE SEASON":
			fmt.Printf("%s is closed for the season.\n", ride.RideName)
		case "TEMPORARILY CLOSED":
			fmt.Printf("%s is temporarily closed.\n", ride.RideName)
		case "UNKNOWN":
			fmt.Printf("%s is closed.\n", ride.RideName)
		default:
			fmt.Printf("%s is in an unknown status: %s\n", ride.RideName, ride.OperationStatus)
		}

		if ride.OperationStatus != "UNKNOWN" {
			stmt, err := db.Prepare("INSERT INTO ride_waits (ride_name, ride_status, wait_time, created_at) VALUES (?, ?, ?, NOW())")
			if err != nil {
				log.Fatal(err)
			}

			_, query_err := stmt.Exec(ride.RideName, ride.OperationStatus, ride.WaitTime)
			if query_err != nil {
				log.Fatal(err)
			}
		}
	}
}
