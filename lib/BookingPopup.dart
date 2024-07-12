import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

class BookingPopup extends StatefulWidget {
  final DateTime selectedDate;

  const BookingPopup({Key? key, required this.selectedDate}) : super(key: key);

  @override
  _BookingPopupState createState() => _BookingPopupState();
}

class _BookingPopupState extends State<BookingPopup> {
  int _selectedRoom = 1; // Default selected room
  String _selectedTime = ''; // Selected time
  bool _bookingSuccess = false;

  void _showBookingSuccessDialog() {
  if (mounted) { // Check if the widget is still mounted
    showDialog(
      context: context,
      barrierDismissible: false, // Prevent dismissing dialog by tapping outside
      builder: (BuildContext context) {
        return AlertDialog(
          backgroundColor: Color(0xFF2B2B2F),
          title: Icon(
            Icons.check_circle_outline,
            color: Color(0xFF746EBD),
            size: 50,
          ),
          content: Text(
            'You have successfully booked!',
            textAlign: TextAlign.center,
            style: TextStyle(
              color: Colors.white,
              fontSize: 20,
              fontFamily: 'Source Sans Pro',
              fontWeight: FontWeight.w700,
            ),
          ),
          actions: <Widget>[
            TextButton(
              child: Text(
                'OK',
                style: TextStyle(
                  color: Color(0xFF746EBD),
                  fontSize: 18,
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w700,
                ),
              ),
              onPressed: () {
                Navigator.of(context).pop(); // Close the success dialog
                Navigator.of(context).pop(); // Close the booking dialog as well
              },
            ),
          ],
        );
      },
    );
  }
}

  @override
  Widget build(BuildContext context) {
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20.0)),
      backgroundColor: Colors.transparent,
      child: Container(
        width: 365,
        height: 495,
        decoration: BoxDecoration(
          color: Color(0xFF2B2B2F),
          borderRadius: BorderRadius.circular(40),
        ),
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Date',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 25,
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w400,
                ),
              ),
              SizedBox(height: 8),
              Text(
                DateFormat.yMMMMd().format(widget.selectedDate),
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 24,
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w300,
                ),
              ),
              SizedBox(height: 40),
              Text(
                'Room',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 25,
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w400,
                ),
              ),
              SizedBox(height: 8),
              Row(
                children: [
                  roomSelectionButton(1),
                  SizedBox(width: 10),
                  roomSelectionButton(2),
                  SizedBox(width: 10),
                  roomSelectionButton(3),
                ],
              ),
              SizedBox(height: 40),
              Text(
                'Time',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 25,
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w400,
                ),
              ),
              SizedBox(height: 8),
              timeSelectionList(), // Widget for selecting time
              SizedBox(height: 40),
              Center(
                child: GestureDetector(
                  onTap: () {
                    // Handle booking logic here
                    if (_selectedTime.isNotEmpty) {
                      print(
                          'Booked room $_selectedRoom at $_selectedTime on ${widget.selectedDate}');
                      Navigator.of(context).pop(); // Close the dialog
                      _handleBooking();
                    } else {
                      showDialog(
                        context: context,
                        builder: (context) => AlertDialog(
                          title: Text('Please select a time'),
                          actions: [
                            TextButton(
                              onPressed: () {
                                Navigator.of(context).pop();
                              },
                              child: Text('OK'),
                            ),
                          ],
                        ),
                      );
                    }
                  },
                  child: Container(
                    width: 182,
                    height: 42.78,
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: Color(0xFF746EBD),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Center(
                      child: Text(
                        'Book Now',
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
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget roomSelectionButton(int roomNumber) {
    return GestureDetector(
      onTap: () {
        setState(() {
          _selectedRoom = roomNumber;
        });
      },
      child: Container(
        width: 50,
        height: 50,
        decoration: BoxDecoration(
          color: _selectedRoom == roomNumber
              ? Color(0xFF746EBD)
              : Color(0xFF575566),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Center(
          child: Text(
            roomNumber.toString(),
            style: TextStyle(
              color: Colors.white,
              fontSize: 20,
              fontFamily: 'Source Sans Pro',
              fontWeight: FontWeight.w700,
            ),
          ),
        ),
      ),
    );
  }

  Widget timeSelectionList() {
    // List of available times
    List<String> times = ['08:00', '10:00', '12:00', '15:00', '17:00', '20:00'];

    return Container(
      height: 50,
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        itemCount: times.length,
        itemBuilder: (context, index) {
          String time = times[index];
          return GestureDetector(
            onTap: () {
              setState(() {
                _selectedTime = time;
              });
            },
            child: Container(
              margin: EdgeInsets.only(right: 10),
              padding: EdgeInsets.symmetric(horizontal: 15, vertical: 10),
              decoration: BoxDecoration(
                color: _selectedTime == time
                    ? Color(0xFF746EBD)
                    : Color(0xFF575566),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Text(
                time,
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 16,
                  fontFamily: 'Source Sans Pro',
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
          );
        },
      ),
    );
  }

  void _handleBooking() {
    // Simulate booking process (can be replaced with actual logic)
    // For demonstration purposes, we'll use a delay to simulate asynchronous behavior
    Future.delayed(Duration(seconds: 10), () {
      _showBookingSuccessDialog(); // Move this inside the delay callback
    });
  }
}
