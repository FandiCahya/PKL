import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';


class ApiService {
  final String baseUrl;

  ApiService({required this.baseUrl});

  Future<List<Room>> fetchRooms() async {
    final response = await http.get(Uri.parse('$baseUrl/kelolarooms'));

    if (response.statusCode == 200) {
      List<dynamic> data = json.decode(response.body);
      return data.map((json) => Room.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load rooms');
    }
  }

  Future<bool> createBooking(
      int userId, int roomId, String date, String time) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/bookings'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(<String, dynamic>{
          'user_id': userId,
          'room_id': roomId,
          'tgl': date,
          'time_slot_id': time,
          'booking_type': 'room',
          'status': 'Pending',
        }),
      );

      // print('Response status: ${response.statusCode}');
      // print('Response body: ${response.body}');

      if (response.statusCode == 201) {
        return true;
      } else {
        final responseBody = jsonDecode(response.body);
        print('Error: ${responseBody['error']}');
        return false;
      }
    } catch (e) {
      print('Exception: $e');
      return false;
    }
  }

  Future<bool> createBooking2(int userId, int promotionId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/bookings'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(<String, dynamic>{
          'user_id': userId,
          'promotion_id': promotionId,
          'booking_type': 'class',
          'status': 'Pending',
        }),
      );

      // print('Response status: ${response.statusCode}');
      // print('Response body: ${response.body}');

      if (response.statusCode == 201) {
        return true;
      } else {
        final responseBody = jsonDecode(response.body);
        print('Error: ${responseBody['error']}');
        return false;
      }
    } catch (e) {
      print('Exception: $e');
      return false;
    }
  }

  Future<List<TimeSlot>> fetchTimeSlots(int roomId) async {
    final response = await http.get(Uri.parse('$baseUrl/times/room/$roomId'));

    if (response.statusCode == 200) {
      final List<dynamic> data = json.decode(response.body);
      return data.map((item) => TimeSlot.fromJson(item)).toList();
    } else {
      throw Exception('Failed to load time slots');
    }
  }

  Future<List<BlockedTimeSlot>> fetchBlockedTimeSlots(
      DateTime selectedDate) async {
    final url =
        '$baseUrl/blocked-datestimes?date=${DateFormat('yyyy-MM-dd').format(selectedDate)}';
    final response = await http.get(Uri.parse(url));

    if (response.statusCode == 200) {
      final List<dynamic> data = json.decode(response.body);
      return data.map((json) => BlockedTimeSlot.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load blocked time slots');
    }
  }
}

class Room {
  final int id;
  final String nama;
  final int availability;

  Room({
    required this.id,
    required this.nama,
    required this.availability,
  });

  factory Room.fromJson(Map<String, dynamic> json) {
    return Room(
      id: json['id'],
      nama: json['nama'],
      availability: json['availability'],
    );
  }
}

class TimeSlot {
  final int id;
  final String startTime;
  final String endTime;
  final int availability;

  TimeSlot({
    required this.id,
    required this.startTime,
    required this.endTime,
    required this.availability,
  });

  factory TimeSlot.fromJson(Map<String, dynamic> json) {
    return TimeSlot(
      id: json['id'],
      startTime: json['start_time'],
      endTime: json['end_time'],
      availability: json['availability'],
    );
  }
}

class BlockedTimeSlot {
  final int id;
  final DateTime blockedDate;
  final int timeSlotId;
  final String reason;

  BlockedTimeSlot({
    required this.id,
    required this.blockedDate,
    required this.timeSlotId,
    required this.reason,
  });

  factory BlockedTimeSlot.fromJson(Map<String, dynamic> json) {
    return BlockedTimeSlot(
      id: json['id'],
      blockedDate: DateTime.parse(json['blocked_date']),
      timeSlotId: json['time_slot_id'],
      reason: json['reason'],
    );
  }
}
