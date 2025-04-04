import 'package:appwrite/models.dart' as models;
import 'package:equatable/equatable.dart';
import 'package:logging/logging.dart';

// Create a logger instance for this file
final _logger = Logger('VideoModel');

class VideoModel extends Equatable {
  final String id;
  final String title;
  final String description;
  final String videoUrl;
  final String thumbnailUrl;
  final bool featured;
  final String category;
  final DateTime createdAt;
  final DateTime updatedAt;

  const VideoModel({
    required this.id,
    required this.title,
    required this.description,
    required this.videoUrl,
    required this.thumbnailUrl,
    required this.featured,
    required this.category,
    required this.createdAt,
    required this.updatedAt,
  });

  factory VideoModel.fromDocument(models.Document document) {
    try {
      final data = document.data;
      return VideoModel(
        id: document.$id,
        title: data['title']?.toString() ?? 'Untitled',
        description: data['description']?.toString() ?? 'No description available',
        videoUrl: data['videoUrl']?.toString() ?? '',
        thumbnailUrl: data['thumbnailUrl']?.toString() ?? '',
        featured: data['featured'] as bool? ?? false,
        category: data['category']?.toString() ?? 'Uncategorized',
        createdAt: _parseDateTime(data['createdAt']),
        updatedAt: _parseDateTime(data['updatedAt']),
      );
    } catch (e) {
      _logger.severe('Error parsing document: $e');
      rethrow;
    }
  }

  static DateTime _parseDateTime(dynamic dateValue) {
    if (dateValue == null) {
      return DateTime.now();
    }
    
    // If it's already a DateTime, return it
    if (dateValue is DateTime) {
      return dateValue;
    }
    
    // If it's a timestamp (seconds or milliseconds)
    if (dateValue is int) {
      // Check if it's in seconds (10 digits) or milliseconds (13 digits)
      if (dateValue.toString().length <= 10) {
        return DateTime.fromMillisecondsSinceEpoch(dateValue * 1000);
      } else {
        return DateTime.fromMillisecondsSinceEpoch(dateValue);
      }
    }
    
    // If it's a string, try to parse it
    if (dateValue is String) {
      if (dateValue.isEmpty) {
        return DateTime.now();
      }
      
      try {
        // Try standard ISO format first
        return DateTime.parse(dateValue);
      } catch (e) {
        _logger.warning('Error parsing date string: $dateValue, error: $e');
        
        // Try to handle common non-standard formats
        try {
          // Handle Unix timestamp in seconds as string
          if (dateValue.length <= 10 && int.tryParse(dateValue) != null) {
            return DateTime.fromMillisecondsSinceEpoch(int.parse(dateValue) * 1000);
          }
          
          // Handle Unix timestamp in milliseconds as string
          if (dateValue.length <= 13 && int.tryParse(dateValue) != null) {
            return DateTime.fromMillisecondsSinceEpoch(int.parse(dateValue));
          }
          
          // If all parsing attempts fail, return current time
          return DateTime.now();
        } catch (e) {
          _logger.warning('Failed to parse date with fallback methods: $e');
          return DateTime.now();
        }
      }
    }
    
    // Default fallback
    return DateTime.now();
  }

  Map<String, dynamic> toMap() {
    return {
      'title': title,
      'description': description,
      'videoUrl': videoUrl,
      'thumbnailUrl': thumbnailUrl,
      'featured': featured,
      'category': category,
      'createdAt': createdAt.toIso8601String(),
      'updatedAt': updatedAt.toIso8601String(),
    };
  }

  @override
  List<Object?> get props => [
        id,
        title,
        description,
        videoUrl,
        thumbnailUrl,
        featured,
        category,
        createdAt,
        updatedAt,
      ];
} 