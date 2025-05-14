import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'screens/auth/login_screen.dart';
import 'screens/auth/register_screen.dart';
import 'screens/quote/quote_form_screen.dart';
import 'screens/quote/quotation_sheet_screen.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const SteelQuoteApp());
}

class SteelQuoteApp extends StatelessWidget {
  const SteelQuoteApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Steel Quote App',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(primarySwatch: Colors.indigo),
      home: const SplashScreen(),
      routes: {
        '/login': (_) => LoginScreen(),
        '/register': (_) => const RegisterScreen(),
        '/quote': (_) => const QuoteFormScreen(),
        '/quotation_sheet': (_) => const QuotationSheetScreen(),
      },
    );
  }
}

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkLogin();
  }

  Future<void> _checkLogin() async {
    final prefs = await SharedPreferences.getInstance();
    final email = prefs.getString('email');
    final nextRoute = email != null ? '/quote' : '/login';
    Navigator.of(context).pushReplacementNamed(nextRoute);
  }

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(child: CircularProgressIndicator()),
    );
  }
}
