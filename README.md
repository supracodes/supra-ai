# Supra Codes - Chat GPT Command Line Interface

## Installation

```bash
composer global require supracodes/gpt
```

## Usage

```bash
# configure the application
gpt configure

Description:
  Configure the application

Usage:
  configure [options]

Options:
      --api-key[=API-KEY]  The Open AI Apikey
      --org[=ORG]          Identifier for this organization
      --config[=CONFIG]    The path to the configuration file
  -h, --help               Display help for the given command. When no command is given display help for the chat command
  -q, --quiet              Do not output any message
  -V, --version            Display this application version
      --ansi|--no-ansi     Force (or disable --no-ansi) ANSI output
  -n, --no-interaction     Do not ask any interactive question
      --env[=ENV]          The environment the command should run under
  -v|vv|vvv, --verbose     Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

example: gpt configure --api-key=apikey --org=org-xxx
```

### Run the chat

```bash
gpt "Write long random php code"
```

#### output
```php
// Connect to MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve data from database
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// Display data in table format
if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}

// Close database connection
mysqli_close($conn);
```
