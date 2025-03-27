import 'package:flutter/material.dart';
import 'package:video_player/video_player.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'M3U8 Player',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.blue),
        useMaterial3: true,
      ),
      home: const M3U8PlayerScreen(),
    );
  }
}

class M3U8PlayerScreen extends StatefulWidget {                                                                                                                   
  const M3U8PlayerScreen({super.key});

  @override
  State<M3U8PlayerScreen> createState() => _M3U8PlayerScreenState();
}

class _M3U8PlayerScreenState extends State<M3U8PlayerScreen> {
  late VideoPlayerController _controller;
  final TextEditingController _textController = TextEditingController();
  String _currentLink = '';

  @override
  void initState() {
    super.initState();
    _currentLink = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4';
    _initializePlayer(_currentLink);
  }

  void _initializePlayer(String link) async {                                                           
    VideoPlayerController newController;
    try {                                                                                                                 
      newController = VideoPlayerController.networkUrl(Uri.parse(link))
        ..setLooping(true);

      await newController.initialize();
      if (mounted) {                                                                                                          
        setState(() {
          _controller = newController;
        });
        _controller.play();
      }
    } catch (_) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error initializing video')),
        );
      }
    }
  }

  void _playLink() async {                                                                                             
    if (_textController.text.isEmpty || _textController.text == _currentLink) return;
    setState(() {
      _currentLink = _textController.text;
    });
    final newController = VideoPlayerController.networkUrl(Uri.parse(_currentLink))
      ..setLooping(true);
    final oldController = _controller;
    try {
      await newController.initialize();
      if (!mounted) return;
      setState(() {
        _controller = newController;
      });
      _controller.play();
      oldController.dispose();
    } catch (_) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error initializing video')),
        );
      }
      newController.dispose();
      return;
    }
  }

  @override
  void dispose() {
    _controller.dispose();
    _textController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final screenWidth = MediaQuery.of(context).size.width;
    final screenPadding = screenWidth * 0.04;

    return Scaffold(
      backgroundColor: Colors.grey.shade900, 
      appBar: AppBar(
        elevation: 4,
        title: const Text('M3U8 Player', style: TextStyle(color: Colors.white)),
        backgroundColor: theme.colorScheme.surface,
      ),
      body: Padding(
        padding: EdgeInsets.all(screenPadding),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            TextField(
              controller: _textController,
              style: TextStyle(color: theme.colorScheme.onSurface),
              decoration: InputDecoration(
                hintText: 'Paste m3u8 link here',  

                hintStyle: TextStyle(
                  color: theme.colorScheme.onSurface.withAlpha((0.6 * 255).round()),
                ),

                border: OutlineInputBorder(),
                filled: true,
                fillColor: theme.colorScheme.surface,
                  enabledBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: theme.colorScheme.outline, width: 1),
                ),
                focusedBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: theme.colorScheme.primary, width: 2),
                ),
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: _playLink,
                 style: ElevatedButton.styleFrom(
                 backgroundColor: theme.colorScheme.primary,
                  foregroundColor: theme.colorScheme.onPrimary,
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              ),
              child: const Text('Play'),
            ),
            SizedBox(height: screenWidth * 0.05),
            _controller.value.isInitialized
                ?  AspectRatio(
                    key: const ValueKey('videoPlayer'),
                    aspectRatio: _controller.value.aspectRatio,
                    child: VideoPlayer(_controller),
                  )
                : const CircularProgressIndicator(),
          ],
        ),
      ), 
      floatingActionButton: _controller.value.isInitialized ? FloatingActionButton(
              onPressed: () {
                setState(() {
                  _controller.value.isPlaying                                       
                      ? _controller.pause()
                      : _controller.play();
                });
              },
              backgroundColor: theme.colorScheme.primary,
              child: Icon(
                _controller.value.isPlaying
                    ? Icons.pause
                    : Icons.play_arrow,
                color: Colors.white,
              ),
            )
          : null, 
    );
  }
}
