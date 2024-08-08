import 'dart:convert';
import 'package:http/http.dart' as http;

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

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

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

  Future<bool> createBooking2(
      int userId, int promotionId) async {
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

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

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

  Future<List<TimeSlot>> fetchTimeSlots() async {
    final response = await http.get(Uri.parse('$baseUrl/kelolawaktu'));

    if (response.statusCode == 200) {
      final List<dynamic> data = json.decode(response.body);
      return data.map((item) => TimeSlot.fromJson(item)).toList();
    } else {
      throw Exception('Failed to load time slots');
    }
  }
}

class Room {
  final int id;
  final String nama;

  Room({
    required this.id,
    required this.nama,
  });

  factory Room.fromJson(Map<String, dynamic> json) {
    return Room(
      id: json['id'],
      nama: json['nama'],
    );
  }
}

class TimeSlot {
  final int id;
  final String startTime;
  final String endTime;

  TimeSlot({required this.id, required this.startTime, required this.endTime});

  factory TimeSlot.fromJson(Map<String, dynamic> json) {
    return TimeSlot(
      id: json['id'],
      startTime: json['start_time'],
      endTime: json['end_time'],
    );
  }
}