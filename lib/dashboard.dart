import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:cached_network_image/cached_network_image.dart';
import 'package:table_calendar/table_calendar.dart';
import 'package:aplikasi_booking_gym/BookingPopup.dart';
import 'package:shared_preferences/shared_preferences.dart';

class Dashboard extends StatefulWidget {
  const Dashboard({Key? key}) : super(key: key);

  @override
  _DashboardState createState() => _DashboardState();
}

class _DashboardState extends State<Dashboard> {
  List<dynamic> promotions = [];
  CalendarFormat _calendarFormat = CalendarFormat.month;
  DateTime _focusedDay = DateTime.now();
  DateTime? _selectedDay;
  String userName = 'Guest';
  String userImage = 'assets/img/profile.jpg';

  @override
  void initState() {
    super.initState();
    fetchPromotions();
    _loadUserData();
  }

  Future<void> fetchPromotions() async {
    try {
      final response =
          await http.get(Uri.parse('http://127.0.0.1:8000/api/kelolakelas'));
      if (response.statusCode == 200) {
        setState(() {
          promotions = jsonDecode(response.body)['promotions'];
        });
        print('Promotions loaded successfully: $promotions');
      } else {
        print('Failed to load promotions');
      }
    } catch (e) {
      print('Error fetching promotions: $e');
    }
  }

  Future<void> _loadUserData() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      userName = prefs.getString('name') ?? 'Guest';
      userImage = prefs.getString('image') ?? 'assets/img/profile.jpg';
    });
  }

  void _showBookingBottomSheet(DateTime selectedDay) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (BuildContext context) {
        return BookingBottomSheet(selectedDate: selectedDay);
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBody: true,
      extendBodyBehindAppBar: true,
      body: SafeArea(
        child: Stack(
          children: [
            Container(
              color: Color.fromARGB(255, 43, 43, 47),
            ),
            SingleChildScrollView(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.start,
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        Container(
                          width: 40,
                          height: 40,
                          decoration: ShapeDecoration(
                            image: DecorationImage(
                              image: NetworkImage(userImage),
                              fit: BoxFit.fill,
                            ),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(8.0),
                            ),
                          ),
                        ),
                        const SizedBox(width: 22),
                        Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Hi, ',
                              style: TextStyle(
                                color: Colors.white,
                                fontSize: 18,
                                fontFamily: 'Source Sans Pro',
                                fontWeight: FontWeight.w700,
                              ),
                            ),
                            Text(
                              userName,
                              style: TextStyle(
                                color: Colors.white,
                                fontSize: 18,
                                fontFamily: 'Source Sans Pro',
                                fontWeight: FontWeight.w700,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                    const SizedBox(height: 20),
                    Container(
                      height: 2,
                      width: double.infinity,
                      color: Color(0xFF726BBC),
                    ),
                    const SizedBox(height: 34),
                    Text.rich(
                      TextSpan(
                        children: [
                          TextSpan(
                            text: 'Push Limits,',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 20,
                              fontFamily: 'Source Sans Pro',
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          TextSpan(
                            text: ' exceed expectations',
                            style: TextStyle(
                              color: Color(0xFF726CBC),
                              fontSize: 20,
                              fontFamily: 'Source Sans Pro',
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ],
                      ),
                      textAlign: TextAlign.start,
                    ),
                    const SizedBox(height: 30),
                    Align(
                      alignment: Alignment.centerLeft,
                      child: Text(
                        'Special On This Week',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 16,
                          fontFamily: 'Source Sans Pro',
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                    ),
                    const SizedBox(height: 20),
                    SingleChildScrollView(
                      scrollDirection: Axis.horizontal,
                      child: Row(
                        children: promotions.map((promo) {
                          return promoCard(
                              promo['name'],
                              // 'http://127.0.0.1:8000/${promo['image']}');
                              // 'https://i.pinimg.com/736x/b9/36/9a/b9369a23e2a9097e48aca3039e2fb939.jpg',
                              'https://picsum.photos/400/300');
                        }).toList(),
                      ),
                    ),
                    const SizedBox(height: 20),
                    Align(
                      alignment: Alignment.topCenter,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              Text(
                                'Date for ',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 20,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              Text(
                                'Booking GYM',
                                style: TextStyle(
                                  color: Color(0xFF726CBC),
                                  fontSize: 20,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 20),
                          TableCalendar(
                            firstDay: DateTime.utc(1500, 01, 01),
                            lastDay: DateTime.utc(3000, 12, 31),
                            focusedDay: _focusedDay,
                            calendarFormat: _calendarFormat,
                            headerStyle: HeaderStyle(
                              titleCentered: true,
                              formatButtonVisible: false,
                              titleTextStyle: TextStyle(
                                color: Colors.white,
                                fontSize: 20,
                                fontWeight: FontWeight.bold,
                              ),
                              leftChevronIcon:
                                  Icon(Icons.chevron_left, color: Colors.white),
                              rightChevronIcon: Icon(Icons.chevron_right,
                                  color: Colors.white),
                              leftChevronMargin: EdgeInsets.only(left: 20),
                              rightChevronMargin: EdgeInsets.only(right: 20),
                            ),
                            calendarStyle: CalendarStyle(
                              defaultTextStyle: TextStyle(color: Colors.white),
                              todayDecoration: BoxDecoration(
                                color: Color(0xFF726BBC).withOpacity(0.5),
                                shape: BoxShape.circle,
                              ),
                              todayTextStyle: TextStyle(color: Colors.white),
                              selectedDecoration: BoxDecoration(
                                color: Color(0xFF726BBC),
                                shape: BoxShape.circle,
                              ),
                              selectedTextStyle: TextStyle(color: Colors.white),
                              weekendTextStyle: TextStyle(color: Colors.white),
                              outsideDaysVisible: false,
                            ),
                            onDaySelected: (selectedDay, focusedDay) {
                              setState(() {
                                _selectedDay = selectedDay;
                                _focusedDay = focusedDay;
                              });
                              _showBookingBottomSheet(selectedDay);
                            },
                            onPageChanged: (focusedDay) {
                              setState(() {
                                _focusedDay = focusedDay;
                              });
                            },
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
      ),
    );
  }

  Widget promoCard(String title, String imageUrl) {
    print('Trying to load image from: $imageUrl'); // Log URL gambar
    return Container(
      margin: const EdgeInsets.only(right: 16),
      width: 300,
      height: 170,
      child: Card(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(10),
        ),
        child: Stack(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(10),
              child: CachedNetworkImage(
                imageUrl: imageUrl,
                fit: BoxFit.cover,
                width: double.infinity,
                height: double.infinity,
                placeholder: (context, url) => Center(
                  child: CircularProgressIndicator(),
                ),
                errorWidget: (context, url, error) {
                  print('Error loading image: $error'); // Log error
                  return Center(
                    child: Text(
                      'Image failed to load',
                      style: TextStyle(color: Colors.white),
                    ),
                  );
                },
              ),
            ),
            Positioned(
              top: 8,
              left: 8,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: Colors.black.withOpacity(0.6),
                  borderRadius: BorderRadius.circular(5),
                ),
                child: Text(
                  title,
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontFamily: 'Source Sans Pro',
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Widget promoCard(String title, String imageUrl) {
  //   print('Trying to load image from: $imageUrl');
  //   return Container(
  //     margin: const EdgeInsets.only(right: 16),
  //     width: 300,
  //     height: 170,
  //     child: Card(
  //       shape: RoundedRectangleBorder(
  //         borderRadius: BorderRadius.circular(10),
  //       ),
  //       child: Stack(
  //         children: [
  //           ClipRRect(
  //             borderRadius: BorderRadius.circular(10),
  //             child: Image.network(
  //               imageUrl,
  //               fit: BoxFit.cover,
  //               width: double.infinity,
  //               height: double.infinity,
  //               loadingBuilder: (context, child, progress) {
  //                 if (progress == null) return child;
  //                 return Center(
  //                   child: CircularProgressIndicator(),
  //                 );
  //               },
  //               errorBuilder: (context, error, stackTrace) => Center(
  //                 child: Text(
  //                   'Image failed to load',
  //                   style: TextStyle(color: Colors.white),
  //                 ),
  //               ),
  //             ),
  //           ),
  //           Positioned(
  //             top: 8,
  //             left: 8,
  //             child: Container(
  //               padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
  //               decoration: BoxDecoration(
  //                 color: Colors.black.withOpacity(0.6),
  //                 borderRadius: BorderRadius.circular(5),
  //               ),
  //               child: Text(
  //                 title,
  //                 style: TextStyle(
  //                   color: Colors.white,
  //                   fontSize: 16,
  //                   fontFamily: 'Source Sans Pro',
  //                   fontWeight: FontWeight.w600,
  //                 ),
  //               ),
  //             ),
  //           ),
  //         ],
  //       ),
  //     ),
  //   );
  // }
}

void main() {
  runApp(MaterialApp(
    home: Dashboard(),
  ));
}
