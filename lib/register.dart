import 'package:flutter/material.dart';
import 'package:aplikasi_booking_gym/login.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class SignUp extends StatefulWidget {
  SignUp({Key? key}) : super(key: key);

  @override
  _SignUpState createState() => _SignUpState();
}

class _SignUpState extends State<SignUp> {
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _addressController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _confirmPasswordController =
      TextEditingController();

  Future<void> registerUser() async {
    final url = Uri.parse('http://192.168.100.97:8000/api/auth/register');

    if (_passwordController.text != _confirmPasswordController.text) {
      _showErrorDialog('Passwords do not match');
      return;
    }

    try {
      final response = await http.post(
        url,
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(<String, String>{
          'name': _nameController.text,
          'email': _emailController.text,
          'alamat': _addressController.text,
          'no_hp': _phoneController.text,
          'password': _passwordController.text,
        }),
      );

      final responseBody = jsonDecode(response.body);

      if (response.statusCode == 200) {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => LoginPage()),
        );
      } else {
        final errorMessage = responseBody['errors']?.values?.toList()?.first ??
            'An error occurred';
        _showErrorDialog(errorMessage);
      }
    } catch (e) {
      _showErrorDialog('An unexpected error occurred');
    }
  }

  void _showErrorDialog(String message) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (BuildContext context) {
        return AlertDialog(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          backgroundColor: Color.fromARGB(255, 23, 23, 26),
          title: Row(
            children: [
              Icon(
                Icons.error_outline,
                color: Colors.red,
                size: 24,
              ),
              SizedBox(width: 8),
              Text(
                'Error',
                style: TextStyle(
                  color: Colors.red,
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ],
          ),
          content: Text(
            message,
            textAlign: TextAlign.center,
            style: TextStyle(
              color: Color(0xffffffff),
              fontSize: 16,
            ),
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text(
                'OK',
                style: TextStyle(
                  color: Color(0xFF746EBD),
                  fontSize: 16,
                ),
              ),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    double screenWidth = MediaQuery.of(context).size.width;
    double textFieldWidth = screenWidth * 0.8;
    double buttonWidth = screenWidth * 0.6;

    return Scaffold(
      body: Stack(
        children: [
          Container(
            color: Color.fromARGB(255, 43, 43, 47),
          ),
          Center(
            child: SingleChildScrollView(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    'Create Account',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: Color(0xffffffff),
                      fontSize: 36,
                      fontFamily: 'Source Sans Pro',
                      fontWeight: FontWeight.w700,
                      letterSpacing: 1.20,
                    ),
                  ),
                  SizedBox(height: 40),
                  _buildTextField(
                    controller: _nameController,
                    icon: Icons.person,
                    hintText: 'Your Name',
                  ),
                  SizedBox(height: 15),
                  _buildTextField(
                    controller: _emailController,
                    icon: Icons.mail,
                    hintText: 'Your Email',
                  ),
                  SizedBox(height: 15),
                  _buildTextField(
                    controller: _addressController,
                    icon: Icons.home,
                    hintText: 'Your Address',
                  ),
                  SizedBox(height: 15),
                  _buildTextField(
                    controller: _phoneController,
                    icon: Icons.phone,
                    hintText: 'Your Phone Number',
                  ),
                  SizedBox(height: 15),
                  _buildTextField(
                    controller: _passwordController,
                    icon: Icons.key,
                    hintText: 'Your Password',
                    obscureText: true,
                  ),
                  SizedBox(height: 15),
                  _buildTextField(
                    controller: _confirmPasswordController,
                    icon: Icons.key,
                    hintText: 'Confirm Your Password',
                    obscureText: true,
                  ),
                  SizedBox(height: 40),
                  Container(
                    width: buttonWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: GestureDetector(
                      onTap: () {
                        registerUser();
                      },
                      child: Container(
                        height: 45,
                        decoration: BoxDecoration(
                          color: Color(0xFF746EBD),
                          borderRadius: BorderRadius.circular(8),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.3),
                              blurRadius: 4,
                              offset: Offset(0, 2),
                            ),
                          ],
                        ),
                        alignment: Alignment.center,
                        child: Text(
                          'Sign Up',
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 16,
                            fontFamily: 'Source Sans Pro',
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 20),
                  GestureDetector(
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => LoginPage()),
                      );
                    },
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          'You Have Account? ',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 14,
                            fontFamily: 'Source Sans Pro',
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        Text(
                          'Login',
                          style: TextStyle(
                            color: Color(0xFF746EBD),
                            fontSize: 14,
                            fontFamily: 'Source Sans Pro',
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required IconData icon,
    required String hintText,
    bool obscureText = false,
  }) {
    return Container(
      width: MediaQuery.of(context).size.width * 0.8,
      padding: const EdgeInsets.symmetric(horizontal: 10),
      child: TextField(
        controller: controller,
        obscureText: obscureText,
        style: TextStyle(color: Colors.white),
        decoration: InputDecoration(
          prefixIcon: Icon(
            icon,
            color: Colors.white.withOpacity(0.55),
          ),
          filled: true,
          fillColor: Color(0x1CD9D9D9),
          hintText: hintText,
          hintStyle: TextStyle(
            color: Colors.white.withOpacity(0.55),
            fontSize: 12,
            fontFamily: 'Source Sans Pro',
            fontWeight: FontWeight.w400,
          ),
          contentPadding: EdgeInsets.symmetric(vertical: 10, horizontal: 10),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: BorderSide.none,
          ),
        ),
      ),
    );
  }
}

void main() {
  runApp(MaterialApp(
    home: SignUp(),
  ));
}
