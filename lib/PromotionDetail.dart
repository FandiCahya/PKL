import 'package:flutter/material.dart';
import 'payments.dart';
import 'room_api_service.dart';
import 'package:intl/intl.dart';
import 'package:shared_preferences/shared_preferences.dart';

class PromotionDetail extends StatefulWidget {
  final String promotionId;
  final String name;
  final String image;
  final String deskripsi;
  final String price;
  final String tgl;
  final String waktu;
  final String room;
  final String instruktur;
  final String roomName;
  final String instrukturName;

  PromotionDetail(
      {required this.promotionId,
      required this.name,
      required this.image,
      required this.deskripsi,
      required this.price,
      required this.tgl,
      required this.waktu,
      required this.room,
      required this.instruktur,
      required this.roomName,
      required this.instrukturName});

  @override
  _PromotionDetailState createState() => _PromotionDetailState();
}

class _PromotionDetailState extends State<PromotionDetail> {
  late ApiService _apiService;

  @override
  void initState() {
    super.initState();
    _apiService = ApiService(baseUrl: 'http://192.168.100.97:8000/api');
  }

  void _bookNow() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    int? userId = prefs.getInt('id');

    if (userId == null) {
      print('User ID not found in SharedPreferences');
      return;
    }

    bool success =
        await _apiService.createBooking2(userId, int.parse(widget.promotionId));
    if (success) {
      _showBookingSuccessDialog();
    } else {
      _showBookingErrorDialog();
    }
  }

  @override
  Widget build(BuildContext context) {
    final screenWidth = MediaQuery.of(context).size.width;
    final screenHeight = MediaQuery.of(context).size.height;

    return Scaffold(
      appBar: AppBar(
        title: Text(
          widget.name,
          style: TextStyle(
            color: Colors.white,
          ),
        ),
        backgroundColor: Color(0xFF2B2B2F),
        iconTheme: IconThemeData(
          color: Colors.white,
        ),
      ),
      backgroundColor: Color.fromARGB(255, 43, 43, 47),
      body: SafeArea(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Expanded(
              child: SingleChildScrollView(
                padding: EdgeInsets.all(screenWidth * 0.04),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      width: double.infinity,
                      height: screenWidth * 0.5,
                      decoration: BoxDecoration(
                        image: DecorationImage(
                          image: NetworkImage(widget.image),
                          fit: BoxFit.cover,
                        ),
                        borderRadius: BorderRadius.circular(10),
                        boxShadow: [
                          BoxShadow(
                            color: Color(0x3F000000),
                            blurRadius: 4,
                            offset: Offset(0, 4),
                          ),
                        ],
                      ),
                    ),
                    SizedBox(height: screenWidth * 0.04),
                    Text(
                      widget.name,
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: screenWidth * 0.08,
                        fontFamily: 'Source Sans Pro',
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    SizedBox(height: screenWidth * 0.04),
                    Text(
                      widget.deskripsi,
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: screenWidth * 0.04,
                        fontFamily: 'Source Sans Pro',
                        fontWeight: FontWeight.w300,
                      ),
                    ),
                    SizedBox(height: screenWidth * 0.04),
                    _buildDetailRow(widget.roomName, 'assets/img/icons/room (1).png', screenWidth),
                    SizedBox(height: screenWidth * 0.04),
                    _buildDetailRow(widget.tgl, 'assets/img/icons/calendar.png', screenWidth),
                    SizedBox(height: screenWidth * 0.04),
                    _buildDetailRow(widget.waktu, 'assets/img/icons/clock.png', screenWidth),
                    SizedBox(height: screenWidth * 0.04),
                    _buildDetailRow(widget.price, 'assets/img/icons/price-tag.png', screenWidth),
                    SizedBox(height: screenWidth * 0.04),
                    _buildDetailRow(widget.instrukturName, 'assets/img/icons/instructor.png', screenWidth),
                    SizedBox(height: screenWidth * 0.06),
                  ],
                ),
              ),
            ),
            Padding(
              padding: EdgeInsets.all(screenWidth * 0.04),
              child: Container(
                width: double.infinity,
                height: screenHeight * 0.06,
                decoration: BoxDecoration(
                  color: Color(0xFF746EBD),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: TextButton(
                  onPressed: _bookNow,
                  child: Text(
                    'Book Now',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: screenWidth * 0.04,
                      fontFamily: 'Source Sans Pro',
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDetailRow(String value, String iconUrl, double screenWidth) {
    return Row(
      children: [
        Image.asset(
          iconUrl,
          width: screenWidth * 0.06,
          height: screenWidth * 0.06,
        ),
        SizedBox(width: screenWidth * 0.02),
        Expanded(
          child: Text(
            '  $value',
            style: TextStyle(
              color: Colors.white,
              fontSize: screenWidth * 0.04,
              fontFamily: 'Source Sans Pro',
              fontWeight: FontWeight.w300,
            ),
          ),
        ),
      ],
    );
  }

  void _showBookingSuccessDialog() {
    if (mounted) {
      showDialog(
        context: context,
        barrierDismissible: false,
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
                  Navigator.of(context).pop();
                  Navigator.of(context)
                      .pop(); // Close the PromotionDetail screen
                },
              ),
            ],
          );
        },
      );
    }
  }

  void _showBookingErrorDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20.0),
        ),
        backgroundColor: Color(0xFF2B2B2F),
        title: Row(
          children: [
            Icon(
              Icons.error_outline,
              color: Colors.redAccent,
              size: 40,
            ),
            SizedBox(width: 10),
            Text(
              'Booking Failed',
              style: TextStyle(
                color: Colors.white,
                fontSize: 24,
                fontFamily: 'Source Sans Pro',
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
        content: Text(
          'An error occurred while booking. Please try again.',
          style: TextStyle(
            color: Colors.white,
            fontSize: 18,
            fontFamily: 'Source Sans Pro',
            fontWeight: FontWeight.w400,
          ),
        ),
        actions: [
          TextButton(
            onPressed: () {
              Navigator.of(context).pop();
            },
            child: Text(
              'OK',
              style: TextStyle(
                color: Color(0xFF746EBD),
                fontSize: 18,
                fontFamily: 'Source Sans Pro',
                fontWeight: FontWeight.w700,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
