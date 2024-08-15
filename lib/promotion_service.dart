import 'dart:convert';
import 'package:http/http.dart' as http;

class PromotionService {
  Future<List<dynamic>> fetchPromotions() async {
    final response =
        await http.get(Uri.parse('http://192.168.100.97:8000/api/kelolakelas'));
    // print("Hello");
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      // print(data['promotions']);
      return data['promotions'];
    } else {
      throw Exception('Failed to fetch promotions');
    }
  }
}
