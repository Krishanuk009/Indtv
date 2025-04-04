import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:logging/logging.dart';
import 'features/video_player/video_list_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Initialize logging
  Logger.root.level = Level.ALL;
  Logger.root.onRecord.listen((record) {
    debugPrint('${record.level.name}: ${record.time}: ${record.message}');
  });
  
  await dotenv.load(fileName: ".env");
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Video Library',
      theme: ThemeData(
        colorScheme: ColorScheme.dark(
          primary: const Color(0xFF00ACFF),
          secondary: const Color(0xFF4C57EE),
          tertiary: const Color(0xFFFD0071),
          surface: const Color(0xFF0B0B0E),
          onSurface: Colors.white,
        ),
        scaffoldBackgroundColor: const Color(0xFF0B0B0E),
        appBarTheme: const AppBarTheme(
          backgroundColor: Color(0xFF0B0B0E),
          elevation: 0,
          centerTitle: true,
        ),
        cardTheme: CardTheme(
          color: const Color(0xFF15181F),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(8),
          ),
        ),
        textTheme: const TextTheme(
          titleLarge: TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.w600,
            color: Colors.white,
          ),
          bodyMedium: TextStyle(
            fontSize: 14,
            color: Colors.white70,
          ),
        ),
        useMaterial3: true,
      ),
      home: const VideoListScreen(),
    );
  }
}
