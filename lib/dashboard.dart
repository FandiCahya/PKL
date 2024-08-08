// lib/screens/dashboard.dart

import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:cached_network_image/cached_network_image.dart';
import 'package:table_calendar/table_calendar.dart';
import 'package:aplikasi_booking_gym/BookingPopup.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:aplikasi_booking_gym/PromotionDetail.dart';
import 'package:aplikasi_booking_gym/promotion_service.dart'; // Import the service

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
  String userImage =
      'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8fXV2eeV0pxoIQx0CdAtrP_tqNuHTApyoCQ&s';
  final PromotionService _promotionService =
      PromotionService(); // Initialize the service

  @override
  void initState() {
    super.initState();
    _loadUserData();
    _fetchPromotions(); // Fetch promotions on init
  }

  Future<void> _loadUserData() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      userName = prefs.getString('name') ?? 'Guest';
      String imagePath = prefs.getString('image') ??
          'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8fXV2eeV0pxoIQx0CdAtrP_tqNuHTApyoCQ&s';
      userImage = 'http://127.0.0.1:8000/$imagePath';
      print('User image URL: $userImage');
    });
  }

  Future<void> _fetchPromotions() async {
    try {
      final data = await _promotionService.fetchPromotions();
      setState(() {
        promotions = data;
      });
    } catch (e) {
      print('Failed to fetch promotions: $e');
    }
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
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(8.0),
                          ),
                          child: Image.network(
                            userImage,
                            fit: BoxFit.fill,
                            errorBuilder: (context, error, stackTrace) {
                              return Center(
                                child: Icon(Icons.error, color: Colors.red),
                              );
                            },
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
                            promo['id'].toString(),
                            promo['name'],
                            'http://127.0.0.1:8000/${promo['image']}',
                            promo['deskripsi'],
                            promo['harga'],
                            promo['tgl'],
                            promo['waktu'],
                            promo['room_id'].toString(),
                            promo['instruktur_id'].toString(),
                            promo['room']?['nama'] ?? 'Unknown Room',
                            promo['instruktur']?['nama'] ?? 'Unknown Instructor',
                          );
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
                              _focusedDay = focusedDay;
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

  Widget promoCard(String id, String name, String imageUrl, String description,
      String price, String date, String waktu, String room, String instruktur, String roomName, String instrukturName) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => PromotionDetail(
              promotionId: id,
              name: name,
              image: imageUrl,
              deskripsi: description,
              price: price,
              tgl: date,
              waktu: waktu,
              room: room,
              instruktur: instruktur,
              roomName: roomName,
              instrukturName: instrukturName,
              
            ),
          ),
        );
      },
      child: Container(
        margin: const EdgeInsets.only(right: 16),
        width: 300,
        height: 170,
        child: Card(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(10),
          ),
          child: Stack(
            children: [
              CachedNetworkImage(
                imageUrl: imageUrl,
                fit: BoxFit.cover,
                width: double.infinity,
                height: double.infinity,
                errorWidget: (context, url, error) => Icon(Icons.error),
              ),
              Container(
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(10),
                  gradient: LinearGradient(
                    colors: [
                      Colors.black.withOpacity(0.7),
                      Colors.transparent,
                    ],
                    begin: Alignment.bottomCenter,
                    end: Alignment.topCenter,
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      name,
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 8),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

void main() {
  runApp(MaterialApp(
    home: Dashboard(),
  ));
}
