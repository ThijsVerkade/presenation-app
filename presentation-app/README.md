# Presentation App

A web-based presentation system designed to run on Raspberry Pi, creating a WiFi access point for clients to connect and view presentations.

## Features

- Creates a dedicated WiFi network for presentation viewing
- Admin interface for controlling presentations
- Client view for audience members
- Docker-based deployment for easy setup
- Optional internet sharing for connected devices

## Installation

### Prerequisites

- Raspberry Pi (tested on Raspberry Pi 4)
- Raspbian OS or similar Debian-based distribution
- WiFi adapter (built-in on Raspberry Pi 4)
- Ethernet connection (optional, for internet sharing)

### Automatic Installation (Recommended)

The easiest way to install the presentation system is using the provided installation script:

First set the correct base dir and replace thijs.verkade with the base location of your RPI

```bash
# Clone the repository
git clone https://github.com/yourusername/presentation-app.git
cd presentation-app

# Make the installation script executable
chmod +x install.sh

# Run the installation script
sudo ./install.sh
```

The installation script will:
1. Install Docker and Docker Compose
2. Set up a WiFi access point with SSID "PresentationNetwork"
3. Configure optional internet sharing
4. Start the Docker containers
5. Set up auto-start services

### Manual Installation

If you prefer to install the components manually:

1. **Install Docker and Docker Compose**:
   ```bash
   curl -fsSL https://get.docker.com -o get-docker.sh
   sh get-docker.sh
   sudo usermod -aG docker $USER
   sudo apt update
   sudo apt install -y docker-compose
   ```

2. **Set up the WiFi Access Point**:
   ```bash
   chmod +x setup-access-point.sh
   sudo ./setup-access-point.sh
   ```

3. **Enable Internet Sharing (Optional)**:
   ```bash
   chmod +x enable-external-access.sh
   sudo ./enable-external-access.sh
   ```

4. **Start the Docker Containers**:
   ```bash
   chmod +x docker-start.sh
   ./docker-start.sh
   ```

5. **Set up Auto-start Service**:
   ```bash
   sudo cp presentation.service /etc/systemd/system/
   sudo systemctl daemon-reexec
   sudo systemctl enable presentation.service
   sudo systemctl start presentation.service
   ```

### Docker-only Installation

If you already have a network setup and just want to run the application:

```bash
# Clone the repository
git clone https://github.com/yourusername/presentation-app.git
cd presentation-app

# Build and start the Docker containers
docker-compose up -d
```

The application will be available at http://localhost:8080

## Usage

1. Connect to the "PresentationNetwork" WiFi network (password: YourStrongPassword)
2. Open a web browser and navigate to http://192.168.4.1:8080
3. Admin interface is available at http://192.168.4.1:8080/admin
4. Client view is available at http://192.168.4.1:8080/client

## License

[MIT License](LICENSE)
