import 'package:flutter/material.dart';
import 'package:steel_quote_app/config/app_config.dart';
import 'register_screen.dart'; // your existing screen
//import '../quote/quote_form_screen.dart'; // TODO: your actual home after login
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';


class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final emailController = TextEditingController();
  final codeController = TextEditingController();
  bool loading = false;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  void login() async {
    setState(() => loading = true);

    final response = await http.post(
      Uri.parse('$BASE_URL/api/login.php'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'email': emailController.text.trim(),
        'invite_code': codeController.text.trim(),
      }),
    );

    final res = jsonDecode(response.body);
    
    setState(() => loading = false);

  if (res['status'] == 'success') {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setInt('user_id', res['user_id']); // you must return user_id from backend
    await prefs.setString('email', emailController.text.trim());

   Navigator.pushReplacementNamed(context, '/quote');
    } else {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(res['message']), backgroundColor: Colors.red),
    );
  }
  }

@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(title: Text('Welcome to Steel Quotation App')),
    body: Column(
      children: [
        TabBar(
          controller: _tabController,
          tabs: [Tab(text: 'Login'), Tab(text: 'Request Access')],
        ),
        Expanded(
          child: TabBarView(
            controller: _tabController,
            children: [
              // Login Tab
              Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    TextField(
                      controller: emailController,
                      decoration: InputDecoration(labelText: 'Email'),
                    ),
                    TextField(
                      controller: codeController,
                      decoration: InputDecoration(labelText: 'Invite Code'),
                    ),
                    SizedBox(height: 20),
                    ElevatedButton(
                      onPressed: loading ? null : login,
                      child: loading ? CircularProgressIndicator() : Text('Login'),
                    ),
                  ],
                ),
              ),
              // Register Tab
              Center(
                child: ElevatedButton(
                  child: Text('Request Access'),
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(builder: (_) => RegisterScreen()),
                    );
                  },
                ),
              ),
            ],
          ),
        )
      ],
    ),
  );
}
}