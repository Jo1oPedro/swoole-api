# Filename: run-docker.ps1

# Set the paths to your Docker Compose files and .env file
$dockerComposeFiles = "docker-compose.yaml", "docker-compose.dev.yaml"
$envFile = ".env.local"

# Construct the Docker Compose command
$dockerComposeCommand = "docker-compose"
$dockerComposeArguments = "-f", ($dockerComposeFiles -join ' -f '), "--env-file", $envFile, "up", "--build", "-d"

# docker compose -f docker-compose.yaml -f docker-compose.dev.yaml --env-file .env.local up --build -d

# Run the Docker Compose command using Start-Process
Start-Process -FilePath $dockerComposeCommand -ArgumentList $dockerComposeArguments -NoNewWindow -Wait
