import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:steel_quote_app/config/app_config.dart';


class QuoteService {


  static Future<Map<String, dynamic>> calculateQuote(Map<String, dynamic> input) async {
    final response = await http.post(
      Uri.parse('$BASE_URL/calculate_quote.php'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode(input),
    );
    

    if (response.statusCode == 200) {

      final data = jsonDecode(response.body);
      if (data['status'] == 'success') {
        return data['data'];
      } else {
        throw Exception(data['message'] ?? 'Calculation failed');
      }
    } else {
      throw Exception('Server error: ${response.statusCode}');
    }
  }
      // get steel types from the server
  static Future<List<Map<String, dynamic>>> fetchSteelTypes() async {
    final response = await http.get(
      Uri.parse('$BASE_URL/api/get_steel_types.php'));
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return List<Map<String, dynamic>>.from(data['data']);
    } else {
      throw Exception('Failed to load steel types');
    }
  }


   static Future<List<Map<String, dynamic>>> fetchWidths() async {
    final response = await http.get(Uri.parse('$BASE_URL/api/get_widths.php'));
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return List<Map<String, dynamic>>.from(data['data']);
    } else {
      throw Exception('Failed to load widths');
    }
  }

   static Future <List<Map<String, dynamic>>> fetchThicknesses() async { 
   final response = await http.get(Uri.parse('$BASE_URL/api/get_thickness_list.php'));
    if (response.statusCode == 200){
        final data = jsonDecode(response.body);
      return List<Map<String, dynamic>>.from(data['data']);
    } else {
      throw Exception('Failed to load thicknesses');
    }    
  }
    // You will continue adding more:
  // static Future<List<Map<String, dynamic>>> fetchCoilWeights() async { ... }
  // static Future<List<Map<String, dynamic>>> fetchPackingTypes() async { ... }
  // static Future<List<Map<String, dynamic>>> fetchProcessingOptions() async { ... }
  // static Future<List<Map<String, dynamic>>> fetchShippingPorts() async { ... }
}
