import 'package:flutter/material.dart';
import 'payments.dart'; // Make sure to import the PaymentScreen file
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
  // int _selectedPromotion = -1;
  late ApiService _apiService;

  @override
  void initState() {
    super.initState();
    _apiService = ApiService(baseUrl: 'http://127.0.0.1:8000/api');
  }

  void _bookNow() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    int? userId = prefs.getInt('id');
    print(userId);

    if (userId == null) {
      print('User ID not found in SharedPreferences');
      return;
    }

    bool success =
        await _apiService.createBooking2(userId, int.parse(widget.promotionId));
    if (success) {
      print('Booking successful');
      print(userId);
      print(int.parse(widget.promotionId));
      _showBookingSuccessDialog();
    } else {
      print('Booking failed');
      print(userId);
      print(int.parse(widget.promotionId));
      _showBookingErrorDialog();
    }
  }

  @override
  Widget build(BuildContext context) {
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
          color: Colors.white, // Set all icons in the AppBar to white
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Container(
            color: Color.fromARGB(255, 43, 43, 47), // Background color
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: double.infinity,
                  height: 207,
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
                SizedBox(height: 16),
                Text(
                  widget.name,
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 30,
                    fontFamily: 'Source Sans Pro',
                    fontWeight: FontWeight.w600,
                  ),
                ),
                SizedBox(height: 16),
                Text(
                  widget.deskripsi,
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 15,
                    fontFamily: 'Source Sans Pro',
                    fontWeight: FontWeight.w300,
                  ),
                ),
                SizedBox(height: 16),
                _buildDetailRow(
                  widget.roomName,
                  'assets/img/icons/room (1).png',
                ),
                SizedBox(height: 16),
                _buildDetailRow(
                  widget.tgl,
                  'assets/img/icons/calendar.png',
                ),
                SizedBox(height: 16),
                _buildDetailRow(
                  widget.waktu,
                  'assets/img/icons/clock.png',
                ),
                SizedBox(height: 16),
                _buildDetailRow(
                  widget.price,
                  'assets/img/icons/price-tag.png',
                ),
                SizedBox(height: 16),
                _buildDetailRow(
                  widget.instrukturName,
                  'assets/img/icons/instructor.png',
                ),
                SizedBox(height: 20),
                Center(
                  child: GestureDetector(
                    onTap: _bookNow,
                    child: Container(
                      width: 184,
                      height: 42,
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
                SizedBox(height: 20), // Add some space at the bottom
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildDetailRow(String value, String iconUrl) {
    return Row(
      children: [
        Image.asset(
          iconUrl,
          width: 25,
          height: 25,
        ),
        SizedBox(width: 8),
        Expanded(
          child: Text(
            '  $value',
            style: TextStyle(
              color: Colors.white,
              fontSize: 15,
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
