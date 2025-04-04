import 'package:appwrite/appwrite.dart';
import 'package:appwrite/models.dart' as models;
import 'package:flutter_dotenv/flutter_dotenv.dart';

class AppwriteService {
  static final Client client = Client()
    ..setEndpoint(dotenv.env['APPWRITE_ENDPOINT'] ?? '')
    ..setProject(dotenv.env['APPWRITE_PROJECT_ID'] ?? '');

  static final Databases databases = Databases(client);
  static final Account account = Account(client);
  static final Storage storage = Storage(client);

  static String get databaseId => dotenv.env['APPWRITE_DATABASE_ID'] ?? '';
  static String get collectionId => dotenv.env['APPWRITE_COLLECTION_ID'] ?? '';

  // Video related methods
  Future<List<models.Document>> getVideos() async {
    try {
      final response = await databases.listDocuments(
        databaseId: databaseId,
        collectionId: collectionId,
        queries: [
          Query.orderDesc('createdAt'),
        ],
      );
      return response.documents;
    } catch (e) {
      throw Exception('Failed to fetch videos: $e');
    }
  }

  Future<List<models.Document>> getFeaturedVideos() async {
    try {
      final response = await databases.listDocuments(
        databaseId: databaseId,
        collectionId: collectionId,
        queries: [
          Query.equal('featured', true),
          Query.orderDesc('createdAt'),
        ],
      );
      return response.documents;
    } catch (e) {
      throw Exception('Failed to fetch featured videos: $e');
    }
  }

  Future<models.Document> getVideoById(String videoId) async {
    try {
      return await databases.getDocument(
        databaseId: databaseId,
        collectionId: collectionId,
        documentId: videoId,
      );
    } catch (e) {
      throw Exception('Failed to fetch video: $e');
    }
  }

  // Auth related methods
  Future<models.User> createAccount({
    required String email,
    required String password,
    required String name,
  }) async {
    try {
      final user = await account.create(
        userId: ID.unique(),
        email: email,
        password: password,
        name: name,
      );
      return user;
    } catch (e) {
      throw Exception('Failed to create account: $e');
    }
  }

  Future<models.Session> login({
    required String email,
    required String password,
  }) async {
    try {
      return await account.createEmailSession(
        email: email,
        password: password,
      );
    } catch (e) {
      throw Exception('Failed to login: $e');
    }
  }

  Future<void> logout() async {
    try {
      await account.deleteSession(sessionId: 'current');
    } catch (e) {
      throw Exception('Failed to logout: $e');
    }
  }
} 