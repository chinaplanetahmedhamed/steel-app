
import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:steel_quote_app/utils/logout_helper.dart';

class QuotationSheetScreen extends StatelessWidget {
  final List<Map<String, dynamic>> items;
  final String notes;
  const QuotationSheetScreen({super.key, this.items = const [], this.notes = ''});
  @override
  Widget build(BuildContext context) {
    double totalTons = 0;
    double totalPrice = 0;
    double totalMeters = 0;
    int totalCoils = 0;

    for (var item in items) {
      totalTons += item['qty'];
      totalPrice += item['qty'] * item['pricePerTon'];
      totalMeters += item['meters'];
      totalCoils += (item['coils'] as int);
    }

    return Scaffold(
      appBar: AppBar(title: Text('Quotation Sheet'),
         actions: [
            IconButton(
              icon: Icon(Icons.logout),
              tooltip: 'Logout',
              onPressed: () => showLogoutConfirmation(context),
            ),
          ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            Expanded(
              child: ListView(
                children: [
                  DataTable(
                    columns: const [
                      DataColumn(label: Text('Material')),
                      DataColumn(label: Text('Thickness')),
                      DataColumn(label: Text('Qty')),
                      DataColumn(label: Text('Unit Price')),
                      DataColumn(label: Text('Total')),
                    ],
                    rows: items
                        .map((item) => DataRow(cells: [
                              DataCell(Text(item['material'])),
                              DataCell(Text('${item['thickness']} mm')),
                              DataCell(Text('${item['qty']} T')),
                              DataCell(Text('\$${item['pricePerTon']}')),
                              DataCell(Text('\$${item['qty'] * item['pricePerTon']}')),
                            ]))
                        .toList(),
                  ),
                  SizedBox(height: 20),
                  Card(
                    child: ListTile(
                      title: Text("Total Qty: $totalTons Tons"),
                      subtitle: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text("Total Coils: $totalCoils"),
                          Text("Total Meters: ${totalMeters.toStringAsFixed(0)} m"),
                          Text("Total Price: \$${totalPrice.toStringAsFixed(2)}"),
                        ],
                      ),
                    ),
                  ),
                  SizedBox(height: 20),
                  TextField(
                    decoration: InputDecoration(
                      labelText: 'Any more requirements? Tell us...',
                      border: OutlineInputBorder(),
                    ),
                    maxLines: 3,
                  ),
                ],
              ),
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              children: [
                ElevatedButton.icon(onPressed: () {}, icon: Icon(Icons.send), label: Text("Send Order")),
                ElevatedButton.icon(onPressed: () {}, icon: Icon(Icons.add), label: Text("Add Items")),
              ],
            ),
            SizedBox(height: 10),
Row(
  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
  children: [
    IconButton(
      onPressed: () async {
        final phone = '+123456789'; // your WhatsApp number
        final message = Uri.encodeComponent("Hello, I'm interested in a steel quote.");
        final url = Uri.parse("https://wa.me/$phone?text=$message");

        if (await canLaunchUrl(url)) {
          await launchUrl(url, mode: LaunchMode.externalApplication);
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text("Could not open WhatsApp")),
          );
        }
      },
      icon: FaIcon(FontAwesomeIcons.whatsapp, color: Colors.green),
    ),
    IconButton(onPressed: () {}, icon: Icon(Icons.picture_as_pdf)),
    IconButton(onPressed: () {}, icon: Icon(Icons.mail_outline)),
  ],
)
          ],
        ),
      ),
    );
  }
}
