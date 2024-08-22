import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'bottom_navigation_bar.dart'; // Import file bottom_navigation_bar.dart
import 'register.dart'; // Import file register.dart
import 'package:flutter_svg/flutter_svg.dart';
import 'package:shared_preferences/shared_preferences.dart';

class LoginPage extends StatelessWidget {
  const LoginPage({super.key});

  @override
  Widget build(BuildContext context) {
    double screenWidth = MediaQuery.of(context).size.width;
    double textFieldWidth = screenWidth * 0.8;

    final TextEditingController emailController = TextEditingController();
    final TextEditingController passwordController = TextEditingController();

    Future<void> loginUser() async {
      final response = await http.post(
        Uri.parse('http://192.168.100.97:8000/api/auth/login'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(<String, String>{
          'email': emailController.text,
          'password': passwordController.text,
        }),
      );
      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');
      final jsonResponse = jsonDecode(response.body);

      if (response.statusCode == 200) {
        // Save user data to SharedPreferences
        final prefs = await SharedPreferences.getInstance();
        prefs.setInt('id', jsonResponse['id']);
        prefs.setString('email', jsonResponse['email']);
        prefs.setString('name', jsonResponse['name']);
        prefs.setString('alamat', jsonResponse['alamat']);
        prefs.setString('no_hp', jsonResponse['no_hp']);
        prefs.setString('image', jsonResponse['image']);
        prefs.setString('status', jsonResponse['status']);

        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (context) => BottomNavigation(),
          ),
        );
      } else {
        print('Login failed: ${jsonResponse['message']}');
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              backgroundColor: Color(0xFF2B2B2F), // Background color
              title: Row(
                children: [
                  Icon(
                    Icons.error_outline,
                    color: Colors.redAccent,
                    size: 30,
                  ),
                  SizedBox(width: 10),
                  Text(
                    'Login Failed',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 22,
                      fontFamily: 'Source Sans Pro',
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ],
              ),
              content: Text(
                jsonResponse['message'] ?? 'An error occurred',
                // jsonResponse['message'] ?? 'Incorrect email or password. Please try again.',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 16,
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w400,
                ),
              ),
              actions: [
                TextButton(
                  onPressed: () {
                    Navigator.of(context).pop();
                  },
                  child: Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    decoration: BoxDecoration(
                      color: Color(0xFF746EBD), // Button background color
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Text(
                      'OK',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 18,
                        fontFamily: 'Source Sans Pro',
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ),
                ),
              ],
            );
          },
        );
      }
    }

    return Scaffold(
      body: Stack(
        children: [
          Container(
            color: Color.fromARGB(255, 43, 43, 47),
          ),
          // Padding(padding: padding)
          Padding(
            padding: const EdgeInsets.symmetric(vertical: 20),
            child: Center(
              child: SingleChildScrollView(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      'FitSpot',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        color: Color(0xFF746EBD),
                        fontSize: 39,
                        fontFamily: 'Source Sans Pro',
                        fontWeight: FontWeight.w700,
                        letterSpacing: 1.20,
                      ),
                    ),
                    SizedBox(height: 15),
                    Container(
                      height: 300,
                      width: screenWidth * 0.9, // Responsive width
                      child: SvgPicture.asset(
                        'assets/img/Workout-amico.svg',
                        fit: BoxFit.contain,
                      ),
                    ),
                    SizedBox(height: 15),
                    Container(
                      width: textFieldWidth,
                      padding: const EdgeInsets.symmetric(horizontal: 10),
                      child: MouseRegion(
                        cursor: SystemMouseCursors.text,
                        child: TextField(
                          controller: emailController,
                          style: TextStyle(color: Colors.white),
                          decoration: InputDecoration(
                            filled: true,
                            fillColor: Color(0x1CD9D9D9),
                            hintText: 'Email',
                            hintStyle: TextStyle(
                              color: Colors.white.withOpacity(0.55),
                              fontSize: 14,
                              fontFamily: 'Source Sans Pro',
                              fontWeight: FontWeight.w400,
                            ),
                            contentPadding: EdgeInsets.symmetric(
                              vertical: 15,
                              horizontal: 15,
                            ),
                            prefixIcon: Icon(Icons.email,
                                color: Colors.white.withOpacity(0.55)),
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(10),
                              borderSide: BorderSide.none,
                            ),
                          ),
                        ),
                      ),
                    ),
                    SizedBox(height: 15),
                    Container(
                      width: textFieldWidth,
                      padding: const EdgeInsets.symmetric(horizontal: 10),
                      child: MouseRegion(
                        cursor: SystemMouseCursors.text,
                        child: TextField(
                          controller: passwordController,
                          obscureText: true,
                          style: TextStyle(color: Colors.white),
                          decoration: InputDecoration(
                            filled: true,
                            fillColor: Color(0x1CD9D9D9),
                            hintText: 'Password',
                            hintStyle: TextStyle(
                              color: Colors.white.withOpacity(0.55),
                              fontSize: 14,
                              fontFamily: 'Source Sans Pro',
                              fontWeight: FontWeight.w400,
                            ),
                            contentPadding: EdgeInsets.symmetric(
                              vertical: 15,
                              horizontal: 15,
                            ),
                            prefixIcon: Icon(Icons.lock,
                                color: Colors.white.withOpacity(0.55)),
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(10),
                              borderSide: BorderSide.none,
                            ),
                          ),
                        ),
                      ),
                    ),
                    SizedBox(height: 25),
                    MouseRegion(
                      cursor: SystemMouseCursors.click,
                      child: GestureDetector(
                        onTap: loginUser,
                        child: Container(
                          width: textFieldWidth * 0.5, // Smaller button width
                          height: 45, // Smaller button height
                          decoration: BoxDecoration(
                            color: Color(0xFF746EBD),
                            borderRadius: BorderRadius.circular(10),
                            boxShadow: [
                              BoxShadow(
                                color: Colors.black.withOpacity(0.5),
                                spreadRadius: 2,
                                blurRadius: 5,
                                offset: Offset(0, 3),
                              ),
                            ],
                          ),
                          alignment: Alignment.center,
                          child: Text(
                            'Sign In',
                            textAlign: TextAlign.center,
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 18,
                              fontFamily: 'Source Sans Pro',
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                        ),
                      ),
                    ),
                    SizedBox(height: 10),
                    Row(
                      mainAxisSize: MainAxisSize.min,
                      mainAxisAlignment: MainAxisAlignment.center,
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        Text(
                          'Not Registered yet? ',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 12,
                            fontFamily: 'Source Sans Pro',
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        GestureDetector(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(builder: (context) => SignUp()),
                            );
                          },
                          child: Text(
                            'Register',
                            style: TextStyle(
                              color: Color(0xFF746EBD),
                              fontSize: 12,
                              fontFamily: 'Source Sans Pro',
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
