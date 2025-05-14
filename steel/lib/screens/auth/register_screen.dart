import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../../styles/input_styles.dart';
import '../../styles/button_styles.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  _RegisterScreenState createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _formKey = GlobalKey<FormState>();
  bool loading = false;

  // ✅ Controllers for all input fields
  final nameController = TextEditingController();
  final emailController = TextEditingController();
  final companyController = TextEditingController();
  final countryController = TextEditingController();
  final phoneController = TextEditingController();
  final notesController = TextEditingController();

  // ✅ Submit and then clear form fields
  Future<void> submitRegistration() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => loading = true);

    try {
  final response = await http.post(
    Uri.parse('http://10.0.2.2:8888/app/steel-app/steel-backend/api/register.php'),
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: {
      'name': nameController.text,
      'email': emailController.text,
      'company': companyController.text,
      'country': countryController.text,
      'phone': phoneController.text,
      'notes': notesController.text,
    },
  );

  final json = jsonDecode(response.body);

  final snackBar = SnackBar(
    content: Text(json['message'] ?? 'Unknown response'),
    backgroundColor: json['success'] == true ? Colors.green : Colors.red,
  );
  ScaffoldMessenger.of(context).showSnackBar(snackBar);

  if (json['success'] == true) {
    nameController.clear();
    emailController.clear();
    companyController.clear();
    countryController.clear();
    phoneController.clear();
    notesController.clear();
  }
} catch (e) {
  print('API call failed: $e');
  ScaffoldMessenger.of(context).showSnackBar(
    SnackBar(content: Text('Error sending request')),
  );
}

  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Request Access  "please tell us your information"')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: ListView(children: [
            TextFormField(
              controller: nameController,
              decoration: inputStyle('Full Name', Icons.person),
              validator: (val) => val!.isEmpty ? 'Required' : null,
            ),
            SizedBox(height: 12),
            TextFormField(
              controller: emailController,
              decoration: inputStyle('Email', Icons.email),
              validator: (val) => val!.isEmpty ? 'Required' : null,
            ),
            SizedBox(height: 12),
            TextFormField(
              controller: companyController,
              decoration: inputStyle('Company', Icons.business),
              validator: (val) => val!.isEmpty ? 'Required' : null,
            ),
            SizedBox(height: 12),
            TextFormField(
              controller: countryController,
              decoration: inputStyle('Country', Icons.flag),
              validator: (val) => val!.isEmpty ? 'Required' : null,
            ),
            SizedBox(height: 12),
            TextFormField(
              controller: phoneController,
              decoration: inputStyle('Phone', Icons.phone),
              validator: (val) => val!.isEmpty ? 'Required' : null,
            ),
            SizedBox(height: 12),
            TextFormField(
              controller: notesController,
              decoration: inputStyle('Notes', Icons.note_alt),
              maxLines: 3,
            ),
            SizedBox(height: 24),
            ElevatedButton(
              style: primaryButton,
              onPressed: loading ? null : submitRegistration,
              child: loading
                  ? CircularProgressIndicator(color: Colors.white)
                  : Text('Submit'),
            ),
            SizedBox(height: 16),
            Text(
              '⚠️ Please make sure your email is correct. Approval may take up to 24 hours.',
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: 16,
                color: Colors.grey[700],
              ),
            ),
          ]),
        ),
      ),
    );
  }
}
