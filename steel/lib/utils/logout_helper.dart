import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

Future<void> logoutUser(BuildContext context) async {
  final prefs = await SharedPreferences.getInstance();
  await prefs.clear();

  Navigator.pushNamedAndRemoveUntil(context, '/login', (route) => false);
}

Future<void> showLogoutConfirmation(BuildContext context) async {
  final confirm = await showDialog<bool>(
    context: context,
    builder: (context) => AlertDialog(
      title: Text('Confirm Logout'),
      content: Text('Are you sure you want to log out?'),
      actions: [
        TextButton(
          child: Text('Cancel'),
          onPressed: () => Navigator.of(context).pop(false),
        ),
        TextButton(
          child: Text('Logout'),
          onPressed: () => Navigator.of(context).pop(true),
        ),
      ],
    ),
  );

  if (confirm == true) {
    await logoutUser(context);
  }
}
