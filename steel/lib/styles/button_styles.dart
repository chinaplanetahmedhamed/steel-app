import 'package:flutter/material.dart';
import 'colors.dart';

final primaryButton = ElevatedButton.styleFrom(
  backgroundColor: AppColors.primary,
  foregroundColor: Colors.white,
  padding: EdgeInsets.symmetric(vertical: 16),
  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
  textStyle: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
);
