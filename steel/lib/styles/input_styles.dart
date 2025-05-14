import 'package:flutter/material.dart';
import 'colors.dart';

InputDecoration inputStyle(String label, IconData icon) {
  return InputDecoration(
    labelText: label,
    filled: true,
    fillColor: AppColors.white,
    prefixIcon: Icon(icon, color: AppColors.primary),
    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
    focusedBorder: OutlineInputBorder(
      borderSide: BorderSide(color: AppColors.primary, width: 2),
      borderRadius: BorderRadius.circular(12),
    ),
  );
}
