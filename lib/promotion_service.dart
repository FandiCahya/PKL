import 'dart:convert';
import 'package:http/http.dart' as http;

class PromotionService {
  Future<List<dynamic>> fetchPromotions() async {
    final response =
        await http.get(Uri.parse('http://127.0.0.1:8000/api/kelolakelas'));
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      // print(data['promotions']);
      return data['promotions'];
    } else {
      throw Exception('Failed to fetch promotions');
    }
  }
}
