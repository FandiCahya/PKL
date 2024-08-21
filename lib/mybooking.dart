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
  DateTime? _startDate;
  DateTime? _endDate;
  String userId = '';
  List<Map<String, dynamic>> bookings = [];
  List<Map<String, dynamic>> filteredBookings = [];

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
        .get(Uri.parse('http://192.168.100.97:8000/api/detailbooking/$userId'));

    print('Response status: ${response.statusCode}');
    print('Response body: ${response.body}');

    if (response.statusCode == 200) {
      setState(() {
        bookings = List<Map<String, dynamic>>.from(jsonDecode(response.body));
        _filterBookings();
      });
    } else {
      print('Failed to load bookings');
    }
  }

  void _filterBookings() {
    setState(() {
      if (_startDate != null && _endDate != null) {
        filteredBookings = bookings.where((booking) {
          DateTime bookingDate = DateFormat('yyyy-MM-dd').parse(booking['tgl']);
          return bookingDate.isAfter(_startDate!.subtract(Duration(days: 1))) &&
              bookingDate.isBefore(_endDate!.add(Duration(days: 1)));
        }).toList();
      } else {
        filteredBookings = bookings;
      }
      // Sort bookings by date in descending order
      filteredBookings.sort((a, b) {
        DateTime dateA = DateFormat('yyyy-MM-dd').parse(a['tgl']);
        DateTime dateB = DateFormat('yyyy-MM-dd').parse(b['tgl']);
        return dateB.compareTo(dateA); // Descending order
      });
    });
  }

  void _showDateRangePicker() async {
    final DateTimeRange? picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime(2000),
      lastDate: DateTime(2101),
      initialDateRange: DateTimeRange(
        start: _startDate ?? DateTime.now(),
        end: _endDate ?? DateTime.now(),
      ),
      builder: (context, child) {
        return Theme(
          data: ThemeData.dark().copyWith(
            primaryColor: Color.fromARGB(255, 43, 43, 47),
            // accentColor: Colors.white,
            datePickerTheme: DatePickerThemeData(
              backgroundColor: Color.fromARGB(
                  255, 43, 43, 47), // Background color of the date picker
              headerBackgroundColor: Color.fromARGB(
                  255, 43, 43, 47), // Background color of the header
              dayStyle: TextStyle(color: Colors.white),
              rangePickerBackgroundColor: Color.fromARGB(255, 43, 43, 47),
            ),
            textButtonTheme: TextButtonThemeData(
              style: TextButton.styleFrom(
                iconColor: Colors.white, // Text color for TextButton
              ),
            ),
            buttonTheme: ButtonThemeData(
              textTheme: ButtonTextTheme.primary, // Text color for buttons
              buttonColor: Color(0xFF746EBD), // Button color
            ),
          ),
          child: child!,
        );
      },
    );

    if (picked != null) {
      setState(() {
        _startDate = picked.start;
        _endDate = picked.end;
        _filterBookings();
      });
    }
  }

  void _showBookingDetailDialog({
    required String id,
    required String kelas,
    required String date,
    required String room,
    required String time,
    required String price,
    required String qrcode,
    required String status,
  }) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return BookingDetailDialog(
          id: id,
          kelas: kelas,
          date: date,
          room: room,
          time: time,
          price: price,
          qrcode: qrcode,
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
              padding: const EdgeInsets.all(16.0),
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
                                  fontSize: 28,
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
                                  fontSize: 28,
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
                  SizedBox(height: screenHeight * 0.02),
                  Center(
                    child: Container(
                      padding:
                          EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                      decoration: BoxDecoration(
                        color: Color(0xFF746EBD),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: GestureDetector(
                        onTap: _showDateRangePicker,
                        child: Text(
                          'Select Date Range',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 16,
                            fontFamily: 'Source Sans Pro',
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: screenHeight * 0.02),
                  Expanded(
                    child: filteredBookings.isEmpty
                        ? Center(
                            child: Text(
                              'No bookings available',
                              style: TextStyle(color: Colors.white),
                            ),
                          )
                        : SingleChildScrollView(
                            child: Column(
                              children: filteredBookings.map((booking) {
                                return BookingItem(
                                  id: booking['id']?.toString() ?? '',
                                  kelas: booking['promotion']?['name'] ?? '',
                                  date: booking['tgl'] ?? '',
                                  room: booking['room']['nama'] ?? '',
                                  time: booking['promotion_time'] ?? '',
                                  price: booking['harga']?.toString() ?? '',
                                  qrcode:
                                      'http://192.168.100.97:8000/${booking['qrcode']}',
                                  status: booking['status'] ?? '',
                                  onTap: () {
                                    _showBookingDetailDialog(
                                      id: booking['id']?.toString() ?? '',
                                      kelas:
                                          booking['promotion']?['name'] ?? '',
                                      date: booking['tgl'] ?? '',
                                      room: booking['room']['nama'] ?? '',
                                      time: booking['promotion_time'] ?? '',
                                      price: booking['harga']?.toString() ?? '',
                                      qrcode:
                                          'http://192.168.100.97:8000/${booking['qrcode']}',
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
  final String id;
  final String kelas;
  final String date;
  final String room;
  final String time;
  final String price;
  final String qrcode;
  final String status;
  final VoidCallback onTap; // Callback for item tap

  const BookingItem({
    Key? key,
    required this.id,
    required this.kelas,
    required this.date,
    required this.room,
    required this.time,
    required this.price,
    required this.qrcode,
    required this.status,
    required this.onTap, // Initialize callback
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final screenWidth = MediaQuery.of(context).size.width;
    final isLargeScreen = screenWidth > 1024;

    return Center(
      child: GestureDetector(
        onTap: onTap, // Call the callback when tapped
        child: Container(
          width: isLargeScreen ? screenWidth * 0.5 : double.infinity,
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
                  fontSize: 20, // Mengurangi ukuran teks
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w600,
                ),
              ),
              SizedBox(height: screenWidth * 0.02), // Add some spacing
              Text(
                'Class: $kelas',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 18, // Ukuran teks untuk kelas
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w400,
                ),
              ),
              SizedBox(height: screenWidth * 0.01),
              Row(
                children: [
                  Expanded(
                    child: Text(
                      'Room: $room',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 20, // Mengurangi ukuran teks
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
                        fontSize: 20, // Mengurangi ukuran teks
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
                        fontSize: 20, // Mengurangi ukuran teks
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
      ),
    );
  }
}
