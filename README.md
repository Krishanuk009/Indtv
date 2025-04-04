# Video Library App

A Flutter application that displays and plays videos using ExoPlayer and Appwrite as the backend.

## Features

- Featured videos carousel
- Video list with thumbnails
- Video playback with controls
- Dark theme with blue/purple gradient accents
- Responsive design

## Appwrite Setup

This app uses Appwrite as the backend. Follow these steps to set up your Appwrite backend:

1. Create an Appwrite account at [appwrite.io](https://appwrite.io/)
2. Create a new project
3. Create a database
4. Create a collection with the following attributes:

### Collection: `videos`

| Attribute Name | Type      | Required | Default | Description                    |
|---------------|-----------|----------|---------|--------------------------------|
| title         | string    | Yes      | -       | Title of the video             |
| description   | string    | Yes      | -       | Description of the video       |
| videoUrl      | string    | Yes      | -       | URL to the video file          |
| thumbnailUrl  | string    | Yes      | -       | URL to the thumbnail image     |
| featured      | boolean   | Yes      | false   | Whether the video is featured  |
| category      | string    | Yes      | -       | Category of the video          |
| createdAt     | string    | Yes      | -       | ISO 8601 date string           |
| updatedAt     | string    | Yes      | -       | ISO 8601 date string           |

### Indexes

Create the following indexes for efficient querying:

1. `featured_index` on the `featured` attribute (boolean)
2. `created_at_index` on the `createdAt` attribute (string)

### Storage

Create a storage bucket for your video files and thumbnail images.

## Environment Variables

Create a `.env` file in the root of the project with the following variables:

```
APPWRITE_PROJECT_ID=your_project_id
APPWRITE_DATABASE_ID=your_database_id
APPWRITE_COLLECTION_ID=your_collection_id
APPWRITE_ENDPOINT=https://cloud.appwrite.io/v1
```

## Getting Started

1. Clone the repository
2. Run `flutter pub get` to install dependencies
3. Update the `.env` file with your Appwrite credentials
4. Run `flutter run` to start the app

## Dependencies

- appwrite: ^11.0.1
- video_player: ^2.8.2
- chewie: ^1.7.5
- flutter_bloc: ^8.1.4
- equatable: ^2.0.5
- cached_network_image: ^3.3.1
- flutter_dotenv: ^5.1.0
- path_provider: ^2.1.2
- permission_handler: ^11.3.0
- carousel_slider: ^4.2.1
