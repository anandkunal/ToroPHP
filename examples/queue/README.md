# Queue

A very simple MySQL-backed queue written with Toro.

## Notes

- This example shows how standard and `xhr` handler methods can be used to build a queuing interface
- Sending a message uses a standard `POST` and redirect
- Receiving a message and updated statistics happens via `XHR` (jQuery via Google CDN)
- There are no acknowledgements or transactions (this queue is not production quality)
- Statistics and queue payloads are stored in a MySQL database - please see installation below


## Install

1. Copy this directory to your server/virtual host root
2. Grab a copy of `toro.php` from the repository and store in the `lib/` directory
3. Create a database called `toroqueue` and execute the `toroqueue.sql` dump
4. Adjust your MySQL database/user information in `lib/mysql.php`