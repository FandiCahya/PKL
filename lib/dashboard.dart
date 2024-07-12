import 'package:aplikasi_booking_gym/mybooking.dart';
import 'package:aplikasi_booking_gym/myprofile.dart';
import 'package:flutter/material.dart';
import 'package:table_calendar/table_calendar.dart';
import 'bottom_navigation_bar.dart'; // Import file bottom_navigation_bar.dart
import 'bookingPopup.dart'; // Import file booking_popup.dart

class Dashboard extends StatefulWidget {
  const Dashboard({Key? key}) : super(key: key);

  @override
  _DashboardState createState() => _DashboardState();
}

class _DashboardState extends State<Dashboard> {
  CalendarFormat _calendarFormat = CalendarFormat.month;
  DateTime _focusedDay = DateTime.now();
  DateTime? _selectedDay;
  int _selectedIndex = 0;

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
            Padding(
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
                            image: NetworkImage(
                              "https://via.placeholder.com/40x40",
                            ),
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
                            'Ferdinan',
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
                      children: [
                        promoCard('Yoga', 'assets/img/yoga.jpg'),
                        promoCard('Boxing', 'assets/img/boxing.jpg'),
                        promoCard('Taekwondo', 'assets/img/taekwondo.jpg'),
                        // Add more cards here
                      ],
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
                            rightChevronIcon:
                                Icon(Icons.chevron_right, color: Colors.white),
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
                            // Show booking popup here
                            showDialog(
                              context: context,
                              builder: (BuildContext context) {
                                return BookingPopup(selectedDate: selectedDay);
                              },
                            );
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
          ],
        ),
      ),
    );
  }

  Widget promoCard(String title, String imagePath) {
    return Container(
      margin: const EdgeInsets.only(right: 16),
      width: 300,
      height: 170,
      child: Card(
        color: Colors.white,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(10),
        ),
        child: Stack(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(10),
              child: Image.asset(
                imagePath,
                fit: BoxFit.cover,
                width: double.infinity,
                height: double.infinity,
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
}
