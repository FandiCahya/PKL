import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'room_api_service.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:collection/collection.dart';

class BookingBottomSheet extends StatefulWidget {
  final DateTime selectedDate;

  const BookingBottomSheet({Key? key, required this.selectedDate})
      : super(key: key);

  @override
  _BookingBottomSheetState createState() => _BookingBottomSheetState();
}

class _BookingBottomSheetState extends State<BookingBottomSheet> {
  int _selectedRoom = -1;
  int _selectedTime = -1;
  late Future<List<Room>> _roomsFuture;
  Future<List<TimeSlot>>? _timeSlotsFuture;
  late ApiService _apiService;

  @override
  void initState() {
    super.initState();
    _apiService = ApiService(baseUrl: 'http://192.168.100.97:8000/api');
    _roomsFuture = _apiService.fetchRooms();
  }

  void _onRoomSelected(int roomId) {
    setState(() {
      _selectedRoom = roomId;
      _timeSlotsFuture = _apiService.fetchTimeSlots(roomId);
    });
  }

  void _showBlockedSlotDialog(String reason) {
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
              Icons.info_outline,
              color: Colors.orangeAccent,
              size: 40,
            ),
            SizedBox(width: 10),
            Text(
              'Time Slot Blocked',
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
          reason,
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
                  Navigator.of(context).pop();
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

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: Color(0xFF2B2B2F),
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(20.0),
          topRight: Radius.circular(20.0),
        ),
      ),
      child: Padding(
        padding: const EdgeInsets.all(24.0),
        child: FutureBuilder<List<Room>>(
          future: _roomsFuture,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              return Center(child: Text('Failed to load rooms'));
            } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
              return Center(child: Text('No rooms available'));
            } else {
              return Column(
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
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: snapshot.data!.map((room) {
                        return roomSelectionButton(room);
                      }).toList(),
                    ),
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
                  FutureBuilder<List<TimeSlot>>(
                    future: _timeSlotsFuture,
                    builder: (context, timeSnapshot) {
                      if (timeSnapshot.connectionState ==
                          ConnectionState.waiting) {
                        return Center(child: CircularProgressIndicator());
                      } else if (timeSnapshot.hasError) {
                        return Center(child: Text('Failed to load times'));
                      } else if (!timeSnapshot.hasData ||
                          timeSnapshot.data!.isEmpty) {
                        return Center(child: Text('No time slots available'));
                      } else {
                        return timeSelectionList(timeSnapshot.data!);
                      }
                    },
                  ),
                  SizedBox(height: 40),
                  Center(
                    child: GestureDetector(
                      onTap: () {
                        if (_selectedTime != -1 && _selectedRoom != -1) {
                          print(
                              'Booked room $_selectedRoom at $_selectedTime on ${widget.selectedDate}');
                          _handleBooking();
                        } else {
                          showDialog(
                            context: context,
                            builder: (context) => AlertDialog(
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(15.0),
                              ),
                              backgroundColor: Color(0xFF2C2C2C),
                              title: Text(
                                'Please select a room and time',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 18,
                                  fontFamily: 'Source Sans Pro',
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                              content: Column(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Text(
                                    'Make sure to select both a room and time slot before proceeding.',
                                    style: TextStyle(
                                      color: Colors.white70,
                                      fontSize: 14,
                                      fontFamily: 'Source Sans Pro',
                                      fontWeight: FontWeight.w400,
                                    ),
                                  ),
                                ],
                              ),
                              actions: [
                                TextButton(
                                  onPressed: () {
                                    Navigator.of(context).pop();
                                  },
                                  style: TextButton.styleFrom(
                                    backgroundColor: Color(0xFF746EBD),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(10),
                                    ),
                                  ),
                                  child: Text(
                                    'OK',
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontSize: 16,
                                      fontFamily: 'Source Sans Pro',
                                      fontWeight: FontWeight.w600,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          );
                        }
                      },
                      child: Container(
                        width: double.infinity,
                        padding: const EdgeInsets.all(12),
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
              );
            }
          },
        ),
      ),
    );
  }

  Widget roomSelectionButton(Room room) {
    bool isSelected = _selectedRoom == room.id;
    return GestureDetector(
      onTap: room.availability == 1 ? () => _onRoomSelected(room.id) : null,
      child: Container(
        margin: EdgeInsets.only(right: 10),
        padding: EdgeInsets.symmetric(horizontal: 15, vertical: 10),
        decoration: BoxDecoration(
          color: isSelected
              ? Color(0xFF746EBD)
              : Colors.white.withOpacity(room.availability == 1 ? 0.1 : 0.3),
          borderRadius: BorderRadius.circular(8.0),
          border: Border.all(
            color: isSelected
                ? Color(0xFF746EBD)
                : Colors.white.withOpacity(room.availability == 1 ? 0.2 : 0.4),
          ),
        ),
        child: Center(
          child: Text(
            room.nama,
            style: TextStyle(
              color: Colors.white,
              fontSize: 16,
              fontFamily: 'Source Sans Pro',
              fontWeight: FontWeight.w700,
            ),
          ),
        ),
      ),
    );
  }

  Widget timeSelectionList(List<TimeSlot> timeSlots) {
    final availableTimeSlots =
        timeSlots.where((slot) => slot.availability == 1).toList();

    if (availableTimeSlots.isEmpty) {
      return Center(
          child: Text('No available time slots',
              style: TextStyle(color: Colors.white)));
    }

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Row(
        children: timeSlots.map((timeSlot) {
          return GestureDetector(
            onTap: timeSlot.availability == 1
                ? () async {
                    List<BlockedTimeSlot> blockedSlots = await _apiService
                        .fetchBlockedTimeSlots(widget.selectedDate);

                    BlockedTimeSlot? blockedSlot =
                        blockedSlots.firstWhereOrNull(
                      (slot) =>
                          slot.timeSlotId == timeSlot.id &&
                          slot.blockedDate.year == widget.selectedDate.year &&
                          slot.blockedDate.month == widget.selectedDate.month &&
                          slot.blockedDate.day == widget.selectedDate.day,
                    );

                    if (blockedSlot != null) {
                      // Jika slot diblokir, tampilkan alasan
                      _showBlockedSlotDialog(blockedSlot.reason);
                    } else {
                      // Jika tidak diblokir, izinkan pemilihan
                      setState(() {
                        _selectedTime = timeSlot.id;
                      });
                    }
                  }
                : null,
            child: Container(
              margin: EdgeInsets.only(right: 10),
              padding: EdgeInsets.symmetric(horizontal: 15, vertical: 10),
              decoration: BoxDecoration(
                color: _selectedTime == timeSlot.id
                    ? Color(0xFF746EBD)
                    : timeSlot.availability == 1
                        ? Color(0xFF424242).withOpacity(0.5)
                        : Color(0xFFBDBDBD).withOpacity(0.5),
                borderRadius: BorderRadius.circular(8.0),
                border: Border.all(
                  color: _selectedTime == timeSlot.id
                      ? Color(0xFF746EBD)
                      : timeSlot.availability == 0
                          ? Color(0xFFE0E0E0).withOpacity(0.3)
                          : Color(0xFF757575).withOpacity(0.3),
                ),
              ),
              child: Center(
                child: Text(
                  '${timeSlot.startTime}',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontFamily: 'Source Sans Pro',
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }

  void _handleBooking() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    int? userId = prefs.getInt('id');

    print('User ID: $userId');
    print('Selected Room: $_selectedRoom');
    print('Selected Time: $_selectedTime');
    print(
        'Selected Date: ${DateFormat('yyyy-MM-dd').format(widget.selectedDate)}');

    if (userId != null && _selectedRoom != -1 && _selectedTime != -1) {
      bool success = await _apiService.createBooking(
        userId,
        _selectedRoom,
        DateFormat('yyyy-MM-dd').format(widget.selectedDate),
        _selectedTime.toString(),
      );

      if (success) {
        _showBookingSuccessDialog();
      } else {
        _showBookingErrorDialog();
      }
    } else {
      _showBookingErrorDialog();
    }
  }
}

void showBookingBottomSheet(BuildContext context, DateTime selectedDate) {
  showModalBottomSheet(
    context: context,
    isScrollControlled: true,
    backgroundColor: Colors.transparent,
    builder: (BuildContext context) {
      return BookingBottomSheet(selectedDate: selectedDate);
    },
  );
}
