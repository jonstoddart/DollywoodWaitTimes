CREATE TABLE ride_waits (
  ride_wait_id int auto_increment primary key,
  ride_name varchar(100),
  ride_status varchar(100),
  wait_time int,
  created_at datetime
);