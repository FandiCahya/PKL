import 'package:aplikasi_booking_gym/dashboard.dart';
import 'package:aplikasi_booking_gym/myprofile.dart';
import 'package:flutter/material.dart';
import 'package:date_picker_timeline/date_picker_timeline.dart';
import 'package:intl/intl.dart'; // To format date
import 'bottom_navigation_bar.dart'; // Assuming this is where you define your BottomNavBar widget

class MyBooking extends StatefulWidget {
  const MyBooking({Key? key}) : super(key: key);

  @override
  State<MyBooking> createState() => _MyBookingState();
}

class _MyBookingState extends State<MyBooking> {
  int _selectedIndex = 1; // Add a variable to track the current tab index

  DateTime _selectedDate = DateTime.now();
  DateTime _firstDate = DateTime.now().subtract(Duration(days: 365)); // 1 year ago
  DateTime _lastDate = DateTime.now(); // Today

  List<Map<String, String?>> bookings = [
    {
      'title': 'GYM',
      'date': '22 December 2023',
      'room': '1',
      'status': 'Booked',
    },
    {
      'title': 'Yoga',
      'date': '12 August 2023',
      'time': '09.00',
      'status': 'Booked',
    },
    // Add more bookings as needed
  ];


  @override
  Widget build(BuildContext context) {
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
                                text: 'Order',
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
                        SizedBox(height: 8), // Adjust the height as needed
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
                  SizedBox(height: 20), // Space between My Order and the calendar
                  // Header for month and year
                  Container(
                    alignment: Alignment.center,
                    child: Text(
                      DateFormat.yMMMM().format(_selectedDate),
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  SizedBox(height: 10), // Space between header and DatePicker
                  // Container for DatePicker with fixed height
                  Container(
                    height: 100,
                    child: DatePicker(
                      DateTime.now(),
                      initialSelectedDate: _selectedDate,
                      selectionColor: Color(0xFF746EBD),
                      selectedTextColor: Colors.white,
                      onDateChange: (date) {
                        if (date.isBefore(_firstDate)) {
                          setState(() {
                            _selectedDate = _firstDate;
                          });
                        } else if (date.isAfter(_lastDate)) {
                          setState(() {
                            _selectedDate = _lastDate;
                          });
                        } else {
                          setState(() {
                            _selectedDate = date;
                          });
                        }
                      },
                    ),
                  ),
                  SizedBox(height: 20), // Space before bookings list
                  Expanded(
                    child: ListView.builder(
                      itemCount: bookings.length,
                      itemBuilder: (context, index) {
                        return BookingItem(
                          title: bookings[index]['title'] ?? '',
                          date: bookings[index]['date'] ?? '',
                          room: bookings[index]['room'] ?? '',
                          time: bookings[index]['time'] ?? '',
                          status: bookings[index]['status'] ?? '',
                        );
                      },
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
  final String title;
  final String date;
  final String room;
  final String time;
  final String status;

  const BookingItem({
    Key? key,
    required this.title,
    required this.date,
    required this.room,
    required this.time,
    required this.status,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: EdgeInsets.symmetric(vertical: 8, horizontal: 16),
      decoration: BoxDecoration(
        border: Border.all(color: Colors.white),
        borderRadius: BorderRadius.circular(10),
      ),
      margin: EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Booked',
            style: TextStyle(
              color: Colors.white,
              fontSize: 23,
              fontFamily: 'Source Sans Pro',
              fontWeight: FontWeight.w600,
            ),
          ),
          SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Flexible(
                child: Text(
                  title,
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 20,
                    fontFamily: 'Source Sans Pro',
                    fontWeight: FontWeight.w300,
                  ),
                ),
              ),
              SizedBox(width: 8),
              Flexible(
                child: Text(
                  date.isNotEmpty ? 'Date : $date' : '',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontFamily: 'Source Sans Pro',
                    fontWeight: FontWeight.w300,
                  ),
                  textAlign: TextAlign.center,
                ),
              ),
              SizedBox(width: 8),
              Flexible(
                child: Text(
                  time.isNotEmpty ? 'Time : $time' : 'Room : $room',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontFamily: 'Source Sans Pro',
                    fontWeight: FontWeight.w300,
                  ),
                  textAlign: TextAlign.center,
                ),
              ),
              SizedBox(width: 8),
              Text(
                'Booked',
                style: TextStyle(
                  color: Color(0xFF726CBC),
                  fontSize: 16,
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w300,
                ),
                textAlign: TextAlign.center,
              ),
            ],
          ),
        ],
      ),
    );
  }
}

// void main() {
//   runApp(MaterialApp(
//     home: MyBooking(),
//   ));
// }
