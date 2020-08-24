# Create a testing database
CREATE DATABASE IF NOT EXISTS test_png;
GRANT ALL PRIVILEGES ON test_png.* TO `png`@`%`;