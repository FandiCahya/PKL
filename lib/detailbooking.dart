import 'package:flutter/material.dart';

class Detailbooking extends StatefulWidget {
  const Detailbooking({super.key});

  @override
  State<Detailbooking> createState() => _DetailbookingState();
}

class _DetailbookingState extends State<Detailbooking> {
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
                                text: 'Detail',
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
                                text: 'Booking',
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
                        SizedBox(height: 8),
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
                  SizedBox(height: 20),
                  Expanded(
                    child: SingleChildScrollView(
                      child: Center(
                        child: Container(
                          width: 319,
                          padding: const EdgeInsets.symmetric(
                              horizontal: 16, vertical: 30),
                          decoration: ShapeDecoration(
                            color: Color(0xFF2B2B2F),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10),
                            ),
                            shadows: [
                              BoxShadow(
                                color: Color(0x3F000000),
                                blurRadius: 7,
                                offset: Offset(0, 4),
                                spreadRadius: 0,
                              )
                            ],
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            children: [
                              SizedBox(
                                width: 183,
                                child: Text(
                                  'Gym',
                                  textAlign: TextAlign.center,
                                  style: TextStyle(
                                    color: Colors.white,
                                    fontSize: 18,
                                    fontFamily: 'Source Sans Pro',
                                    fontWeight: FontWeight.w600,
                                    height: 1.2,
                                  ),
                                ),
                              ),
                              const SizedBox(height: 25),
                              Container(
                                width: 216,
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          'Name',
                                          style: TextStyle(
                                            color: Colors.white,
                                            fontSize: 16,
                                            fontFamily: 'Source Sans Pro',
                                            fontWeight: FontWeight.w600,
                                          ),
                                        ),
                                        Text(
                                          'Ferdinan',
                                          style: TextStyle(
                                            color: Color(0xFF746EBD),
                                            fontSize: 14,
                                            fontFamily: 'Source Sans Pro',
                                            fontWeight: FontWeight.w300,
                                          ),
                                        ),
                                      ],
                                    ),
                                    SizedBox(height: 20),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          'Room',
                                          style: TextStyle(
                                            color: Colors.white,
                                            fontSize: 14,
                                            fontFamily: 'Source Sans Pro',
                                            fontWeight: FontWeight.w600,
                                          ),
                                        ),
                                        Text(
                                          '1',
                                          style: TextStyle(
                                            color: Color(0xFF746EBD),
                                            fontSize: 14,
                                            fontFamily: 'Source Sans Pro',
                                            fontWeight: FontWeight.w300,
                                          ),
                                        ),
                                      ],
                                    ),
                                    SizedBox(height: 20),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          'Date',
                                          style: TextStyle(
                                            color: Colors.white,
                                            fontSize: 14,
                                            fontFamily: 'Source Sans Pro',
                                            fontWeight: FontWeight.w600,
                                          ),
                                        ),
                                        Text(
                                          '22 December 2023',
                                          style: TextStyle(
                                            color: Color(0xFF746EBD),
                                            fontSize: 14,
                                            fontFamily: 'Source Sans Pro',
                                            fontWeight: FontWeight.w300,
                                          ),
                                        ),
                                      ],
                                    ),
                                    SizedBox(height: 20),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          'Status',
                                          style: TextStyle(
                                            color: Colors.white,
                                            fontSize: 14,
                                            fontFamily: 'Source Sans Pro',
                                            fontWeight: FontWeight.w600,
                                          ),
                                        ),
                                        Text(
                                          'Booked',
                                          style: TextStyle(
                                            color: Color(0xFF746EBD),
                                            fontSize: 14,
                                            fontFamily: 'Source Sans Pro',
                                            fontWeight: FontWeight.w300,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                              ),
                              const SizedBox(height: 25),
                              Container(
                                padding: const EdgeInsets.all(10),
                                decoration: BoxDecoration(color: Colors.white),
                                child: Row(
                                  mainAxisSize: MainAxisSize.min,
                                  mainAxisAlignment: MainAxisAlignment.start,
                                  crossAxisAlignment: CrossAxisAlignment.center,
                                  children: [
                                    Container(
                                      width: 150,
                                      height: 150,
                                      decoration: BoxDecoration(
                                        image: DecorationImage(
                                          image: NetworkImage(
                                              "https://via.placeholder.com/150x150"),
                                          fit: BoxFit.fill,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              const SizedBox(height: 25),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            Positioned(
              left: 40,
              right: 40,
              bottom: 20,
              child: GestureDetector(
                onTap: () {
                  // Action when "Done" button is tapped
                },
                child: Container(
                  width: 150,
                  height: 50,
                  decoration: BoxDecoration(
                    color: Color(0xFF746EBD),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Center(
                    child: Text(
                      'Done',
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
            ),
          ],
        ),
      ),
    );
  }
}

// void main() {
//   runApp(MaterialApp(
//     home: Detailbooking(),
//   ));
// }
