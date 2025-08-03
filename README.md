# ComfyWeb - ComfyUI Web Platform

A comprehensive web platform for managing ComfyUI workflows, generating images, and handling LoRAs with automatic trigger word integration. Built with Laravel and designed for easy Docker deployment.

## Features

- **Workflow Management**: Store, load, and manage ComfyUI workflows as JSON files
- **Dynamic UI**: Form fields adapt automatically based on selected workflow (SDXL vs Flux)
- **LoRA Support**: Automatic trigger word integration into prompts
- **Batch Generation**: Generate 1-8 images per request
- **Real-time Status**: Live updates during image generation
- **Responsive Design**: Modern, mobile-friendly interface
- **Docker Ready**: Complete Docker Compose setup for one-click deployment

## Quick Start

### Prerequisites

- Docker and Docker Compose
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ladisch-business/comfyweb.git
   cd comfyweb
   ```

2. **Start the application**
   ```bash
   docker-compose up -d
   ```

3. **Access the application**
   - Web Interface: http://localhost:8080
   - ComfyUI Interface: http://localhost:8188

The application will automatically:
- Set up the database
- Run migrations
- Seed sample workflows and LoRAs
- Start all required services

## Architecture

### Services

- **app**: Laravel application (PHP 8.2 + Nginx)
- **db**: MySQL 8.0 database
- **comfyui**: ComfyUI service with GPU support
- **redis**: Redis for caching and queues

### Ports

- `8080`: Web application
- `8188`: ComfyUI API and interface
- `3306`: MySQL database
- `6379`: Redis

## Usage

### Workflow Management

1. **View Workflows**: Navigate to "Workflows" to see available workflows
2. **Add Workflow**: Upload custom ComfyUI workflow JSON files
3. **Configure Fields**: Define which input fields to show (prompt, negative_prompt)
4. **LoRA Support**: Enable LoRA support for workflows that support it

### LoRA Management

1. **Add LoRAs**: Navigate to "LoRAs" and add new LoRA definitions
2. **Trigger Words**: Define trigger words that get automatically added to prompts
3. **Associate with Workflows**: Link LoRAs to compatible workflows

### Image Generation

1. **Select Workflow**: Choose from SDXL, Flux, or custom workflows
2. **Enter Prompts**: Fill in prompt and negative prompt (if supported)
3. **Choose LoRAs**: Select optional LoRAs (trigger words added automatically)
4. **Set Batch Size**: Choose 1-8 images to generate
5. **Generate**: Start generation and monitor real-time progress
6. **Download**: View and download generated images

## Configuration

### Environment Variables

Key environment variables in `.env`:

```env
# Application
APP_NAME=ComfyWeb
APP_URL=http://localhost:8080

# Database
DB_CONNECTION=mysql
DB_HOST=db
DB_DATABASE=comfyweb
DB_USERNAME=comfyweb
DB_PASSWORD=comfyweb_password

# ComfyUI Integration
COMFYUI_URL=http://comfyui:8188
COMFYUI_TIMEOUT=300
COMFYUI_POLL_INTERVAL=2

# Image Storage
IMAGES_DISK=public
IMAGES_PATH=generated
THUMBNAILS_PATH=thumbnails
```

### Docker Compose Configuration

The `docker-compose.yml` includes:
- GPU support for ComfyUI (NVIDIA runtime required)
- Persistent volumes for database and ComfyUI models
- Network isolation
- Health checks

### Adding Custom Workflows

1. Export workflow JSON from ComfyUI
2. Use `{{prompt}}` placeholder for user input
3. Configure field requirements in the web interface
4. Set LoRA support if applicable

Example workflow structure:
```json
{
  "1": {
    "inputs": {
      "text": "{{prompt}}",
      "clip": ["4", 1]
    },
    "class_type": "CLIPTextEncode"
  }
}
```

## Development

### Local Development

1. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

### File Structure

```
├── app/
│   ├── Http/Controllers/     # Web controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # ComfyUI service integration
│   └── Jobs/                # Background job processing
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # Sample data
├── resources/
│   └── views/               # Blade templates
├── docker/                  # Docker configuration
├── docker-compose.yml       # Service orchestration
└── Dockerfile              # Application container
```

## API Integration

### ComfyUI API Endpoints

- `POST /prompt`: Submit generation request
- `GET /history/{prompt_id}`: Check generation status
- `GET /view`: Download generated images

### Internal API

- `GET /api/workflows/{id}/config`: Get workflow configuration
- `GET /api/generations/{id}/status`: Check generation status

## Troubleshooting

### Common Issues

1. **ComfyUI not starting**: Ensure NVIDIA Docker runtime is installed for GPU support
2. **Database connection failed**: Check if MySQL container is running
3. **Images not generating**: Verify ComfyUI models are properly mounted
4. **Permission errors**: Ensure storage directories are writable

### Logs

View service logs:
```bash
docker-compose logs app
docker-compose logs comfyui
docker-compose logs db
```

### Reset Application

To completely reset:
```bash
docker-compose down -v
docker-compose up -d
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues and questions:
- Create an issue on GitHub
- Check the troubleshooting section
- Review ComfyUI documentation for workflow-related questions
