import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:date_picker_timeline/date_picker_timeline.dart';
import 'package:intl/intl.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'booking_detail.dart'; // Import file booking_detail.dart

class MyBooking extends StatefulWidget {
  const MyBooking({Key? key}) : super(key: key);

  @override
  State<MyBooking> createState() => _MyBookingState();
}

class _MyBookingState extends State<MyBooking> {
  DateTime _selectedDate = DateTime.now();
  DateTime _firstDate =
      DateTime.now().subtract(Duration(days: 365)); // 1 year ago
  DateTime _lastDate = DateTime.now(); // Today
  String userId = '';
  List<Map<String, dynamic>> bookings = [];

  @override
  void initState() {
    super.initState();
    _loadUserData().then((_) {
      fetchBookings();
    });
  }

  Future<void> _loadUserData() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      userId = prefs.getInt('id')?.toString() ?? '';
      print('Loaded user ID: $userId');
    });
  }

  Future<void> fetchBookings() async {
    final response = await http
        .get(Uri.parse('http://127.0.0.1:8000/api/detailbooking/$userId'));

    print('Response status: ${response.statusCode}');
    print('Response body: ${response.body}');

    if (response.statusCode == 200) {
      setState(() {
        bookings = List<Map<String, dynamic>>.from(jsonDecode(response.body));
      });
    } else {
      print('Failed to load bookings');
    }
  }

  void _showBookingDetailDialog({
    required String date,
    required String room,
    required String time,
    required String status,
  }) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return BookingDetailDialog(
          date: date,
          room: room,
          time: time,
          status: status,
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    final screenWidth = MediaQuery.of(context).size.width;
    final screenHeight = MediaQuery.of(context).size.height;

    return Scaffold(
      extendBody: true,
      extendBodyBehindAppBar: true,
      body: SafeArea(
        child: Stack(
          children: [
            Container(
              color: const Color.fromARGB(255, 43, 43, 47),
            ),
            Padding(
              padding: EdgeInsets.all(screenWidth * 0.04),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    width: double.infinity,
                    padding:
                        EdgeInsets.symmetric(vertical: screenHeight * 0.02),
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
                                  fontSize: screenWidth * 0.05,
                                  fontFamily: 'Source Sans Pro',
                                  fontWeight: FontWeight.w600,
                                  height: 1.2,
                                ),
                              ),
                              TextSpan(
                                text: ' ',
                                style: TextStyle(
                                  color: Colors.black,
                                  fontSize: screenWidth * 0.05,
                                  fontFamily: 'Source Sans Pro',
                                  fontWeight: FontWeight.w600,
                                  height: 1.2,
                                ),
                              ),
                              TextSpan(
                                text: 'Order',
                                style: TextStyle(
                                  color: Color(0xFF746EBD),
                                  fontSize: screenWidth * 0.05,
                                  fontFamily: 'Source Sans Pro',
                                  fontWeight: FontWeight.w600,
                                  height: 1.2,
                                ),
                              ),
                            ],
                          ),
                          textAlign: TextAlign.center,
                        ),
                        SizedBox(height: screenHeight * 0.01),
                        Container(
                          height: 2,
                          width: double.infinity,
                          color: Color(0xFF726BBC),
                        ),
                      ],
                    ),
                  ),
                  // SizedBox(height: screenHeight * 0.02),
                  // Center(
                  //   child: Text(
                  //     DateFormat.yMMMM().format(_selectedDate),
                  //     style: TextStyle(
                  //       color: Colors.white,
                  //       fontSize: screenWidth * 0.045,
                  //       fontWeight: FontWeight.bold,
                  //     ),
                  //   ),
                  // ),
                  // SizedBox(height: screenHeight * 0.02),
                  // Container(
                  //   height: screenHeight * 0.12,
                  //   child: Column(
                  //     children: [
                  //       Flexible(
                  //         child: DatePicker(
                  //           DateTime.now(),
                  //           initialSelectedDate: _selectedDate,
                  //           selectionColor: Color(0xFF746EBD),
                  //           selectedTextColor: Colors.white,
                  //           onDateChange: (date) {
                  //             if (date.isBefore(_firstDate)) {
                  //               setState(() {
                  //                 _selectedDate = _firstDate;
                  //               });
                  //             } else if (date.isAfter(_lastDate)) {
                  //               setState(() {
                  //                 _selectedDate = _lastDate;
                  //               });
                  //             } else {
                  //               setState(() {
                  //                 _selectedDate = date;
                  //               });
                  //             }
                  //           },
                  //         ),
                  //       ),
                  //     ],
                  //   ),
                  // ),
                  SizedBox(height: screenHeight * 0.02),
                  Expanded(
                    child: bookings.isEmpty
                        ? Center(
                            child: Text(
                              'No bookings available',
                              style: TextStyle(color: Colors.white),
                            ),
                          )
                        : SingleChildScrollView(
                            child: Column(
                              children: bookings.map((booking) {
                                return BookingItem(
                                  date: booking['tgl'] ?? '',
                                  room: booking['room_id']?.toString() ?? '',
                                  time:
                                      '${booking['start_time'] ?? ''} - ${booking['end_time'] ?? ''}',
                                  status: booking['status'] ?? '',
                                  onTap: () {
                                    _showBookingDetailDialog(
                                      date: booking['tgl'] ?? '',
                                      room:
                                          booking['room_id']?.toString() ?? '',
                                      time:
                                          '${booking['start_time'] ?? ''} - ${booking['end_time'] ?? ''}',
                                      status: booking['status'] ?? '',
                                    );
                                  },
                                );
                              }).toList(),
                            ),
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

class BookingItem extends StatelessWidget {
  final String date;
  final String room;
  final String time;
  final String status;
  final VoidCallback onTap; // Callback for item tap

  const BookingItem({
    Key? key,
    required this.date,
    required this.room,
    required this.time,
    required this.status,
    required this.onTap, // Initialize callback
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final screenWidth = MediaQuery.of(context).size.width;

    return GestureDetector(
      onTap: onTap, // Call the callback when tapped
      child: Container(
        width: double.infinity,
        padding: EdgeInsets.symmetric(
            vertical: screenWidth * 0.02, horizontal: screenWidth * 0.04),
        decoration: BoxDecoration(
          border: Border.all(color: Colors.white),
          borderRadius: BorderRadius.circular(screenWidth * 0.03),
        ),
        margin: EdgeInsets.only(bottom: screenWidth * 0.04),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Status: $status',
              style: TextStyle(
                color: Colors.white,
                fontSize: screenWidth * 0.045,
                fontFamily: 'Source Sans Pro',
                fontWeight: FontWeight.w600,
              ),
            ),
            SizedBox(height: screenWidth * 0.02),
            Row(
              children: [
                Expanded(
                  child: Text(
                    'Room: $room',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: screenWidth * 0.04,
                      fontFamily: 'Source Sans Pro',
                      fontWeight: FontWeight.w300,
                    ),
                  ),
                ),
                SizedBox(width: screenWidth * 0.02),
                Expanded(
                  child: Text(
                    'Date: $date',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: screenWidth * 0.04,
                      fontFamily: 'Source Sans Pro',
                      fontWeight: FontWeight.w300,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ),
                SizedBox(width: screenWidth * 0.02),
                Expanded(
                  child: Text(
                    'Time: $time',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: screenWidth * 0.04,
                      fontFamily: 'Source Sans Pro',
                      fontWeight: FontWeight.w300,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
