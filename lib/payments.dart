import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'dart:io';

class PaymentScreen extends StatefulWidget {
  final String bookingId;
  final String price;

  PaymentScreen({required this.bookingId, required this.price});

  @override
  _PaymentScreenState createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  XFile? _imageFile;
  final ImagePicker _picker = ImagePicker();

  Future<void> _pickImage() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
    setState(() {
      _imageFile = pickedFile;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Upload Payment Proof'),
        backgroundColor: Color(0xFF2B2B2F),
        iconTheme: IconThemeData(
          color: Colors.white, // Set all icons in the AppBar to white
        ),
      ),
      body: SafeArea(
        child: Container(
          color: Color.fromARGB(255, 43, 43, 47), // Background color
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Booking ID: ${widget.bookingId}',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 18,
                  fontFamily: 'Source Sans Pro',
                ),
              ),
              SizedBox(height: 16),
              Text(
                'Price: ${widget.price}',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 18,
                  fontFamily: 'Source Sans Pro',
                ),
              ),
              SizedBox(height: 16),
              Center(
                child: GestureDetector(
                  onTap: _pickImage,
                  child: Container(
                    width: 184,
                    height: 42,
                    decoration: BoxDecoration(
                      color: Color(0xFF746EBD),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Center(
                      child: Text(
                        'Upload Payment Proof',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 16,
                          fontFamily: 'Source Sans Pro',
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ),
                  ),
                ),
              ),
              SizedBox(height: 16),
              if (_imageFile != null)
                Center(
                  child: Column(
                    children: [
                      Image.file(
                        File(_imageFile!.path),
                        height: 150,
                      ),
                      SizedBox(height: 16),
                      GestureDetector(
                        onTap: () {
                          // Submit payment proof
                        },
                        child: Container(
                          width: 184,
                          height: 42,
                          decoration: BoxDecoration(
                            color: Color(0xFF746EBD),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Center(
                            child: Text(
                              'Submit',
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
    home: PaymentScreen(bookingId: '1', price: '5000',),
  ));
}