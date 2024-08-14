import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:image_picker/image_picker.dart';
import 'dart:io';
import 'package:shared_preferences/shared_preferences.dart';
import 'api_service.dart';

class BookingDetailDialog extends StatefulWidget {
  final String id;
  final String kelas;
  final String date;
  final String room;
  final String time;
  final String price;
  final String qrcode;
  final String status;

  const BookingDetailDialog({
    Key? key,
    required this.id,
    required this.kelas,
    required this.date,
    required this.room,
    required this.time,
    required this.price,
    required this.qrcode,
    required this.status,
  }) : super(key: key);

  @override
  _BookingDetailDialogState createState() => _BookingDetailDialogState();
}

class _BookingDetailDialogState extends State<BookingDetailDialog> {
  final ImagePicker _picker = ImagePicker();
  XFile? _imageFile;
  String userId = '';
  final ApiService _apiService = ApiService();

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  Future<void> _pickImage() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
    setState(() {
      _imageFile = pickedFile;
    });
  }

  Future<void> _loadUserData() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      userId = prefs.getInt('id')?.toString() ?? '';
      print('Loaded user ID: $userId');
    });
  }

  Future<void> _payNow() async {
    if (userId.isEmpty || _imageFile == null) {
      _showErrorDialog('Please upload a payment proof first!');
      return;
    }

    final paymentProofFile = File(_imageFile!.path);

    try {
      await _apiService.createPayment(
        userId: userId,
        bookingId: widget.id,
        amount: widget.price,
        paymentProof: paymentProofFile,
      );

      _showSuccessDialog('Payment successful!');
    } catch (e) {
      _showErrorDialog('Payment failed! Please try again.');
    }
  }

  void _showSuccessDialog(String message) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          backgroundColor: const Color(0xFF2B2B2F),
          title: Text(
            'Success',
            style: TextStyle(color: Colors.white),
          ),
          content: Text(
            message,
            style: TextStyle(color: Colors.white),
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
                Navigator.of(context).pop(); // Close the booking details dialog
              },
              child: Text(
                'OK',
                style: TextStyle(color: Colors.blue),
              ),
            ),
          ],
        );
      },
    );
  }

  void _showErrorDialog(String message) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          backgroundColor: const Color(0xFF2B2B2F),
          title: Text(
            'Error',
            style: TextStyle(color: Colors.white),
          ),
          content: Text(
            message,
            style: TextStyle(color: Colors.white),
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text(
                'OK',
                style: TextStyle(color: Colors.red),
              ),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    final screenWidth = MediaQuery.of(context).size.width;

    return AlertDialog(
      backgroundColor: const Color(0xFF2B2B2F),
      content: SingleChildScrollView(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          mainAxisAlignment: MainAxisAlignment.start,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Text(
              'Booking Details',
              textAlign: TextAlign.center,
              style: TextStyle(
                color: Colors.white,
                fontSize: 25,
                fontFamily: 'Source Sans Pro',
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 20),
            _buildDetailRow('Class', widget.kelas),
            const SizedBox(height: 20),
            _buildDetailRow('Time', widget.time),
            const SizedBox(height: 12),
            _buildDetailRow('Room', widget.room),
            const SizedBox(height: 12),
            _buildDetailRow('Date', widget.date),
            const SizedBox(height: 12),
            _buildDetailRow('Price', widget.price),
            const SizedBox(height: 12),
            _buildDetailRow('Status', widget.status),
            const SizedBox(height: 20),
            _buildQrCode(screenWidth),
            const SizedBox(height: 20),
            if (_imageFile != null) _buildUploadedImage(screenWidth),
            const SizedBox(height: 12),
            _buildActionButtons(),
          ],
        ),
      ),
    );
  }

  Widget _buildDetailRow(String title, String value) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          title,
          style: TextStyle(
            color: Colors.white,
            fontSize: 20,
            fontFamily: 'Source Sans Pro',
            fontWeight: FontWeight.w600,
          ),
        ),
        Text(
          value,
          style: TextStyle(
            color: Color(0xFF746EBD),
            fontSize: 20,
            fontFamily: 'Source Sans Pro',
            fontWeight: FontWeight.w300,
          ),
        ),
      ],
    );
  }

  Widget _buildQrCode(double screenWidth) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
      ),
      padding: const EdgeInsets.all(10),
      margin: const EdgeInsets.only(top: 10),
      child: CachedNetworkImage(
        imageUrl: widget.qrcode,
        width: screenWidth * 0.25,
        height: screenWidth * 0.25,
        fit: BoxFit.fill,
        placeholder: (context, url) => CircularProgressIndicator(),
        errorWidget: (context, url, error) => Icon(
          Icons.error,
          color: Colors.red,
        ),
      ),
    );
  }

  Widget _buildUploadedImage(double screenWidth) {
    return Image.file(
      File(_imageFile!.path),
      width: screenWidth * 0.5,
      height: screenWidth * 0.5,
      fit: BoxFit.cover,
    );
  }

  Widget _buildActionButtons() {
      // Kondisi untuk memeriksa status booking
  if (widget.status == 'Booked') {
    // Jika status Booked, kembalikan Container kosong atau SizedBox
    return SizedBox.shrink();
  }

    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Container(
          decoration: BoxDecoration(
            color: Colors.blue,
            borderRadius: BorderRadius.circular(8),
          ),
          padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 20),
          child: GestureDetector(
            onTap: _pickImage,
            child: Text(
              'Upload Payment',
              style: TextStyle(
                color: Colors.white,
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ),
        Container(
          decoration: BoxDecoration(
            color: Color(0xFF746EBD),
            borderRadius: BorderRadius.circular(8),
          ),
          padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 20),
          child: GestureDetector(
            onTap: _payNow,
            child: Text(
              'Pay Now',
              style: TextStyle(
                color: Colors.white,
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ),
      ],
    );
  }
}
