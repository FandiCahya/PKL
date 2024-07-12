import 'package:aplikasi_booking_gym/login.dart';
import 'package:aplikasi_booking_gym/mybooking.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

void main() {

  // Untuk Potrait
  SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp])
      .then((_) {
    runApp(const MyApp());
  });

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: LoginPage(),
    );
  }
}
