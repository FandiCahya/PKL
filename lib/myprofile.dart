import 'package:aplikasi_booking_gym/dashboard.dart';
import 'package:aplikasi_booking_gym/mybooking.dart';
import 'package:flutter/material.dart';
import 'package:aplikasi_booking_gym/editprofile.dart';
import 'package:aplikasi_booking_gym/login.dart';
import 'bottom_navigation_bar.dart'; // Import file bottom_navigation_bar.dart

class Myprofile extends StatefulWidget {
  const Myprofile({Key? key}) : super(key: key);

  @override
  _MyprofileState createState() => _MyprofileState();
}

class _MyprofileState extends State<Myprofile> {
  int _selectedIndex = 2;

  @override
  Widget build(BuildContext context) {
    final double screenWidth = MediaQuery.of(context).size.width;
    final double profileWidth = screenWidth * 0.5;
    final double profileHeight = profileWidth * 1.26;

    return Scaffold(
      extendBody: true,
      extendBodyBehindAppBar: true,
      body: SafeArea(
        child: Stack(
          children: [
            Container(
              color: Color.fromARGB(255, 43, 43, 47),
            ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    width: double.infinity,
                    height: 60,
                    alignment: Alignment.center,
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text.rich(
                          TextSpan(
                            children: [
                              TextSpan(
                                text: 'My',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 20,
                                  fontFamily: 'Source Sans Pro',
                                  fontWeight: FontWeight.w600,
                                  height: 1.2,
                                ),
                              ),
                              TextSpan(
                                text: ' ',
                                style: TextStyle(
                                  color: Colors.black,
                                  fontSize: 20,
                                  fontFamily: 'Source Sans Pro',
                                  fontWeight: FontWeight.w600,
                                  height: 1.2,
                                ),
                              ),
                              TextSpan(
                                text: 'Profile',
                                style: TextStyle(
                                  color: Color(0xFF746EBD),
                                  fontSize: 20,
                                  fontFamily: 'Source Sans Pro',
                                  fontWeight: FontWeight.w600,
                                  height: 1.2,
                                ),
                              ),
                            ],
                          ),
                          textAlign: TextAlign.center,
                        ),
                        SizedBox(height: 8),
                        Container(
                          width: 318,
                          height: 2,
                          decoration: ShapeDecoration(
                            color: Color(0xFF726BBC),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  Expanded(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Center(
                          child: Column(
                            children: [
                              Container(
                                width: profileWidth,
                                height: profileHeight,
                                child: Column(
                                  mainAxisSize: MainAxisSize.min,
                                  mainAxisAlignment: MainAxisAlignment.start,
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Container(
                                      width: double.infinity,
                                      height: profileWidth,
                                      decoration: ShapeDecoration(
                                        image: DecorationImage(
                                          image: AssetImage(
                                              'assets/img/profile.jpg'),
                                          fit: BoxFit.fill,
                                        ),
                                        shape: CircleBorder(
                                          side: BorderSide(
                                              width: 3,
                                              color: Color(0xFF726CBC)),
                                        ),
                                        shadows: [
                                          BoxShadow(
                                            color: Color(0x3F000000),
                                            blurRadius: 4,
                                            offset: Offset(0, 4),
                                            spreadRadius: 0,
                                          ),
                                        ],
                                      ),
                                    ),
                                    const SizedBox(height: 19),
                                    SizedBox(
                                      width: double.infinity,
                                      child: Text(
                                        'Ferdinan',
                                        textAlign: TextAlign.center,
                                        style: TextStyle(
                                          color: Colors.white,
                                          fontSize: 23,
                                          fontFamily: 'Source Sans Pro',
                                          fontWeight: FontWeight.w600,
                                          height: 0,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              const SizedBox(height: 20),
                              GestureDetector(
                                onTap: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (context) => EditMyProfile()),
                                  );
                                },
                                child: Container(
                                  padding: EdgeInsets.symmetric(vertical: 12),
                                  width: profileWidth,
                                  decoration: BoxDecoration(
                                    color: Color(0xFF726CBC),
                                    borderRadius: BorderRadius.circular(8),
                                  ),
                                  child: Text(
                                    'Edit Profile',
                                    textAlign: TextAlign.center,
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontSize: 18,
                                      fontFamily: 'Source Sans Pro',
                                      fontWeight: FontWeight.w600,
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                        GestureDetector(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                  builder: (context) => LoginPage()),
                            );
                          },
                          child: Container(
                            padding: EdgeInsets.symmetric(vertical: 12),
                            width: double.infinity,
                            decoration: BoxDecoration(
                              color: Color(0xFF726CBC),
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Text(
                              'Sign Out',
                              textAlign: TextAlign.center,
                              style: TextStyle(
                                color: Colors.white,
                                fontSize: 18,
                                fontFamily: 'Source Sans Pro',
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// void main() {
//   runApp(MaterialApp(
//     home: Myprofile(),
//   ));
// }
