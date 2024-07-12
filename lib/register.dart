import 'package:flutter/material.dart';
import 'package:aplikasi_booking_gym/login.dart';

class SignUp extends StatelessWidget {
  SignUp({Key? key}) : super(key: key);

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
                    'Create Account',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: Color(0xffffffff),
                      fontSize: 39,
                      fontFamily: 'Source Sans Pro',
                      fontWeight: FontWeight.w700,
                      letterSpacing: 1.20,
                    ),
                  ),
                  SizedBox(
                    height: 50,
                  ),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: TextField(
                      style: TextStyle(color: Colors.white),
                      decoration: InputDecoration(
                        prefixIcon: Icon(
                          Icons.person, // Ikon pengguna
                          color: Colors.white.withOpacity(0.55), // Warna ikon
                        ),
                        filled: true,
                        fillColor: Color(0x1CD9D9D9),
                        hintText: 'Your Name',
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
                  SizedBox(
                    height: 30,
                  ),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: TextField(
                      style: TextStyle(color: Colors.white),
                      decoration: InputDecoration(
                        prefixIcon: Icon(
                          Icons.mail, // Ikon pengguna
                          color: Colors.white.withOpacity(0.55), // Warna ikon
                        ),
                        filled: true,
                        fillColor: Color(0x1CD9D9D9),
                        hintText: 'Your Email',
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
                  SizedBox(
                    height: 30,
                  ),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: TextField(
                      style: TextStyle(color: Colors.white),
                      decoration: InputDecoration(
                        prefixIcon: Icon(
                          Icons.home, // Ikon pengguna
                          color: Colors.white.withOpacity(0.55), // Warna ikon
                        ),
                        filled: true,
                        fillColor: Color(0x1CD9D9D9),
                        hintText: 'Your Address',
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
                  SizedBox(
                    height: 30,
                  ),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: TextField(
                      style: TextStyle(color: Colors.white),
                      decoration: InputDecoration(
                        prefixIcon: Icon(
                          Icons.phone, // Ikon pengguna
                          color: Colors.white.withOpacity(0.55), // Warna ikon
                        ),
                        filled: true,
                        fillColor: Color(0x1CD9D9D9),
                        hintText: 'Your Phone Number',
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
                  SizedBox(
                    height: 30,
                  ),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: TextField(
                      obscureText: true,
                      style: TextStyle(color: Colors.white),
                      decoration: InputDecoration(
                        prefixIcon: Icon(
                          Icons.key, // Ikon pengguna
                          color: Colors.white.withOpacity(0.55), // Warna ikon
                        ),
                        filled: true,
                        fillColor: Color(0x1CD9D9D9),
                        hintText: 'Your Password',
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
                  SizedBox(
                    height: 30,
                  ),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: TextField(
                      obscureText: true,
                      style: TextStyle(color: Colors.white),
                      decoration: InputDecoration(
                        prefixIcon: Icon(
                          Icons.key, // Ikon pengguna
                          color: Colors.white.withOpacity(0.55), // Warna ikon
                        ),
                        filled: true,
                        fillColor: Color(0x1CD9D9D9),
                        hintText: 'Confirm Your Password',
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
                  SizedBox(
                    height: 50,
                  ),
                  Container(
                    width: textFieldWidth,
                    padding: const EdgeInsets.symmetric(horizontal: 10),
                    child: GestureDetector(
                      onTap: () {
                        // Add sign-in functionality here
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
                          'Sign Up',
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
                  SizedBox(
                    height: 10,
                  ),
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
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        Text(
                          'You Have Account? ',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 11,
                            fontFamily: 'Source Sans Pro',
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        Text(
                          'Login',
                          style: TextStyle(
                            color: Color(0xFF746EBD),
                            fontSize: 11,
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
          )
        ],
      ),
    );
  }
}
