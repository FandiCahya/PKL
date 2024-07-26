// room_api_service.dart
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
