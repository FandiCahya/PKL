import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';

class ApiService {
  static const String baseUrl = 'http://192.168.100.97:8000/api';

  // Function to handle payment API
  Future<void> createPayment({
    required String userId,
    required String bookingId,
    required String amount,
    required File paymentProof,
  }) async {
    final uri = Uri.parse('$baseUrl/payments');
    final request = http.MultipartRequest('POST', uri);

    request.fields['user_id'] = userId;
    request.fields['booking_id'] = bookingId;
    request.fields['amount'] = amount;

    // Attach payment proof file if available
    if (paymentProof != null) {
      final stream = http.ByteStream(paymentProof.openRead());
      final length = await paymentProof.length();
      final multipartFile = http.MultipartFile(
        'payment_proof',
        stream,
        length,
        filename: paymentProof.path.split('/').last,
        contentType: MediaType('image', 'jpg'),
      );
      request.files.add(multipartFile);
    }

    final response = await request.send();
    print(response);

    if (response.statusCode == 201) {
      print('Payment created successfully.');
    } else {
      print('Failed to create payment: ${response.reasonPhrase}');
    }
  }
}
