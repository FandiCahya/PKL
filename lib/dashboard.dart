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
  List<dynamic> blockedDates = [];
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
    _fetchBlockedDates(); // Fetch blocked dates on init
  }

  Future<void> _loadUserData() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      userName = prefs.getString('name') ?? 'Guest';
      String imagePath = prefs.getString('image') ??
          'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8fXV2eeV0pxoIQx0CdAtrP_tqNuHTApyoCQ&s';
      userImage = 'http://192.168.100.97:8000/$imagePath';
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

  Future<void> _fetchBlockedDates() async {
    final url = Uri.parse('http://192.168.100.97:8000/api/blocked-dates');
    final response = await http.get(url);

    if (response.statusCode == 200) {
      print('Blocked Dates API Response: ${response.body}');
      setState(() {
        blockedDates = json.decode(response.body);
      });
    } else {
      print('Failed to load blocked dates');
    }
  }

  // void _showBookingBottomSheet(DateTime selectedDay) {
  //   showModalBottomSheet(
  //     context: context,
  //     isScrollControlled: true,
  //     backgroundColor: Colors.transparent,
  //     builder: (BuildContext context) {
  //       // heightFactor:0.75;
  //       return BookingBottomSheet(selectedDate: selectedDay);
  //     },
  //   );
  // }
  void _showBookingBottomSheet(DateTime selectedDay) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (BuildContext context) {
        return FractionallySizedBox(
          heightFactor: 0.75, // Mengatur tinggi menjadi 3/4 layar
          child: BookingBottomSheet(selectedDate: selectedDay),
        );
      },
    );
  }

  void _showBlockedDateDialog(String reason) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          backgroundColor: Color(0xFF2B2B2F),
          title: Row(
            children: [
              Icon(Icons.block, color: Colors.red, size: 30),
              SizedBox(width: 10),
              Text(
                'Date Blocked',
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
            reason,
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
                  color: Color(0xFF746EBD),
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
                          width:
                              40, // Lebih besar untuk tampilan yang lebih baik
                          height: 40,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle, // Menggunakan bentuk bulat
                            border: Border.all(
                              color:
                                  Color(0xFF726BBC), // Border warna abu-abu
                              width: 1.5, // Lebar border
                            ),
                            boxShadow: [
                              BoxShadow(
                                color: Colors.black
                                    .withOpacity(0.2), // Bayangan hitam lembut
                                blurRadius: 6, // Radius blur
                                offset: Offset(0, 2), // Posisi bayangan
                              ),
                            ],
                          ),
                          child: ClipOval(
                            child: Image.network(
                              userImage,
                              fit: BoxFit
                                  .cover, // Memastikan gambar mengisi Container
                              loadingBuilder: (context, child, progress) {
                                if (progress == null) {
                                  return child;
                                } else {
                                  return Center(
                                    child:
                                        CircularProgressIndicator(), // Loader saat gambar dimuat
                                  );
                                }
                              },
                              errorBuilder: (context, error, stackTrace) {
                                return Center(
                                  child: Icon(
                                    Icons.error,
                                    color: Colors.red,
                                    size: 24,
                                  ),
                                );
                              },
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
                    const SizedBox(height: 10),
                    Container(
                      height: 2,
                      width: double.infinity,
                      color: Color(0xFF726BBC),
                    ),
                    const SizedBox(height: 15),
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
                    const SizedBox(height: 10),
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
                    const SizedBox(height: 10),
                    SingleChildScrollView(
                      scrollDirection: Axis.horizontal,
                      child: Row(
                        children: promotions.map((promo) {
                          return promoCard(
                            promo['id'].toString(),
                            promo['name'],
                            'http://192.168.100.97:8000/${promo['image']}',
                            promo['deskripsi'],
                            promo['harga'],
                            promo['tgl'],
                            promo['waktu'],
                            promo['room_id'].toString(),
                            promo['instruktur_id'].toString(),
                            promo['room']?['nama'] ?? 'Unknown Room',
                            promo['instruktur']?['nama'] ??
                                'Unknown Instructor',
                          );
                        }).toList(),
                      ),
                    ),
                    const SizedBox(height: 15),
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
                              disabledTextStyle: TextStyle(
                                color: Colors.red.withOpacity(0.8),
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            onDaySelected: (selectedDay, focusedDay) {
                              bool isBlocked = false;
                              String reason = '';

                              for (var blocked in blockedDates) {
                                DateTime blockedDate =
                                    DateTime.parse(blocked['blocked_date']);
                                if (blockedDate.year == selectedDay.year &&
                                    blockedDate.month == selectedDay.month &&
                                    blockedDate.day == selectedDay.day) {
                                  isBlocked = true;
                                  reason = blocked['reason'];
                                  break;
                                }
                              }

                              if (isBlocked) {
                                _showBlockedDateDialog(
                                    reason); // Show reason for blocking
                              } else {
                                setState(() {
                                  _selectedDay = selectedDay;
                                  _focusedDay = focusedDay;
                                });
                                _showBookingBottomSheet(selectedDay);
                              }
                            },
                            onPageChanged: (focusedDay) {
                              setState(() {
                                _focusedDay = focusedDay;
                              });
                            },
                            calendarBuilders: CalendarBuilders(
                              defaultBuilder: (context, date, _) {
                                bool isBlocked = false;

                                for (var blocked in blockedDates) {
                                  DateTime blockedDate =
                                      DateTime.parse(blocked['blocked_date']);
                                  if (blockedDate.year == date.year &&
                                      blockedDate.month == date.month &&
                                      blockedDate.day == date.day) {
                                    isBlocked = true;
                                    break;
                                  }
                                }

                                if (isBlocked) {
                                  return Center(
                                    child: Container(
                                      width: 40, // Adjust size as needed
                                      height: 40,
                                      decoration: BoxDecoration(
                                        color: Colors.red.withOpacity(
                                            0.8), // Red background color
                                        shape: BoxShape.circle, // Oval shape
                                      ),
                                      alignment: Alignment.center,
                                      child: Text(
                                        '${date.day}',
                                        style: TextStyle(
                                          color: Colors.white,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                    ),
                                  );
                                }
                                return null; // No special style for non-blocked dates
                              },
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
      ),
    );
  }

  Widget promoCard(
      String id,
      String name,
      String imageUrl,
      String description,
      String price,
      String date,
      String waktu,
      String room,
      String instruktur,
      String roomName,
      String instrukturName) {
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
        margin: const EdgeInsets.only(right: 10),
        width: 300,
        height: 170,
        child: Card(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(10), // Buat sudut lebih halus
          ),
          child: Stack(
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(
                    10), // Terapkan border radius pada gambar juga
                child: CachedNetworkImage(
                  imageUrl: imageUrl,
                  fit: BoxFit.cover,
                  width: double.infinity,
                  height: double.infinity,
                  errorWidget: (context, url, error) => Icon(Icons.error),
                ),
              ),
              Container(
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(
                      10), // Sesuaikan border radius pada gradient
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
                    Container(
                      padding: const EdgeInsets.symmetric(
                          vertical: 4, horizontal: 8),
                      decoration: BoxDecoration(
                        color: Colors.black.withOpacity(
                            0.5), // Latar belakang solid untuk nama
                        borderRadius: BorderRadius.circular(
                            8), // Membuat sudut kotak nama lebih halus
                      ),
                      child: Text(
                        name,
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
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
