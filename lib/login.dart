import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'bottom_navigation_bar.dart'; // Import file bottom_navigation_bar.dart
import 'register.dart'; // Import file register.dart

class LoginPage extends StatelessWidget {
  const LoginPage({super.key});

  @override
  Widget build(BuildContext context) {
    double screenWidth = MediaQuery.of(context).size.width;
    double textFieldWidth = screenWidth * 0.8;

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
                    'GYM App',
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
                    width: 417,
                    child: SvgPicture.asset(
                      'assets/img/Workout-amico.svg',
                      fit: BoxFit.contain,
                    ),
                  ),
                  SizedBox(height: 15),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: TextField(
                      style: TextStyle(color: Colors.white),
                      decoration: InputDecoration(
                        filled: true,
                        fillColor: Color(0x1CD9D9D9),
                        hintText: 'Email',
                        hintStyle: TextStyle(
                          color: Colors.white.withOpacity(0.55),
                          fontSize: 12,
                          fontFamily: 'Source Sans Pro',
                          fontWeight: FontWeight.w400,
                        ),
                        contentPadding:
                            EdgeInsets.symmetric(vertical: 10, horizontal: 10),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(10),
                          borderSide: BorderSide.none,
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 15),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: TextField(
                      obscureText: true,
                      style: TextStyle(color: Colors.white),
                      decoration: InputDecoration(
                        filled: true,
                        fillColor: Color(0x1CD9D9D9),
                        hintText: 'Password',
                        hintStyle: TextStyle(
                          color: Colors.white.withOpacity(0.55),
                          fontSize: 12,
                          fontFamily: 'Source Sans Pro',
                          fontWeight: FontWeight.w400,
                        ),
                        contentPadding:
                            EdgeInsets.symmetric(vertical: 10, horizontal: 10),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(10),
                          borderSide: BorderSide.none,
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 25),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: GestureDetector(
                      onTap: () {
                        Navigator.pushReplacement(
                          context,
                          MaterialPageRoute(builder: (context) => BottomNavigation()), // Pindah ke BottomNavigation
                        );
                      },
                      child: Container(
                        height: 55,
                        decoration: ShapeDecoration(
                          color: Color(0xFF746EBD),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(10),
                          ),
                        ),
                        alignment: Alignment.center,
                        child: Text(
                          'Sign In',
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 20,
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
                          fontSize: 11,
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
                            fontSize: 11,
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
        ],
      ),
    );
  }
}
