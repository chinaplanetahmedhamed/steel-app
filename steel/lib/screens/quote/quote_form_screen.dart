import 'package:flutter/material.dart';
import 'package:steel_quote_app/styles/input_styles.dart';
import 'package:steel_quote_app/screens/quote/quotation_sheet_screen.dart';
import 'package:steel_quote_app/utils/logout_helper.dart';
import 'package:steel_quote_app/services/quote_service.dart';
import 'package:steel_quote_app/helpers/calc_helpers.dart';

class QuoteFormScreen extends StatefulWidget {
  const QuoteFormScreen({Key? key}) : super(key: key);

  @override
  State<QuoteFormScreen> createState() => _QuoteFormScreenState();
}

class _QuoteFormScreenState extends State<QuoteFormScreen> {
  final _formKey = GlobalKey<FormState>();

  List<Map<String, dynamic>> steelTypes = [];
  List<Map<String, dynamic>> thicknessOptions = [];
  List<Map<String, dynamic>> widthOptions = [];

  String? selectedSteelTypeLabel;
  String? selectedThicknessLabel;
  String? selectedWidthLabel;

  int? selectedSteelTypeId;
  int? selectedThicknessId;
  int? selectedWidthId;

  @override
  void initState() {
    super.initState();
    loadSteelTypes();
    loadThicknesses();
    loadWidths();
  }

  Future<void> loadSteelTypes() async {
    steelTypes = await QuoteService.fetchSteelTypes();
    setState(() {
      selectedSteelTypeLabel = steelTypes.first['label'];
      selectedSteelTypeId = steelTypes.first['id'];
    });
  }

  Future<void> loadThicknesses() async {
    thicknessOptions = await QuoteService.fetchThicknesses();
    setState(() {
      selectedThicknessLabel = thicknessOptions.first['label'].toString();
      selectedThicknessId = thicknessOptions.first['id'];
    });
  }

  Future<void> loadWidths() async {
    widthOptions = await QuoteService.fetchWidths();
    setState(() {
      selectedWidthLabel = widthOptions.first['label'].toString();
      selectedWidthId = widthOptions.first['id'];
    });
  }

  Widget buildDropdown(
    String label,
    String value,
    List<String> items,
    void Function(String?) onChanged,
  ) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w500),
        ),
        const SizedBox(height: 6),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 12),
          decoration: BoxDecoration(
            border: Border.all(color: Colors.black26),
            borderRadius: BorderRadius.circular(12),
          ),
          child: DropdownButtonHideUnderline(
            child: DropdownButton<String>(
              isExpanded: true,
              value: value,
              items:
                  items
                      .map((e) => DropdownMenuItem(value: e, child: Text(e)))
                      .toList(),
              onChanged: onChanged,
            ),
          ),
        ),
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Steel Quote Form'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            tooltip: 'Logout',
            onPressed: () => showLogoutConfirmation(context),
          ),
        ],
      ),

      body:
          steelTypes.isEmpty
              ? const Center(child: CircularProgressIndicator())
              : SingleChildScrollView(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Basic details',
                      style: Theme.of(context).textTheme.titleMedium,
                    ),

                    //dropdown for steel type
                    const SizedBox(height: 12),
                    buildDropdown(
                      'Steel Type',
                      selectedSteelTypeLabel ?? '',
                      steelTypes.map((e) => e['label'] as String).toList(),
                      (val) => setState(() {
                        selectedSteelTypeLabel = val;
                        selectedSteelTypeId =
                            steelTypes.firstWhere(
                              (e) => e['label'] == val,
                            )['id'];
                      }),
                    ),

                    const SizedBox(height: 12), //space between dropdowns
                    //dropdown for thickness and width
                    Row(
                      children: [
                        Expanded(
                          child: buildDropdown(
                            // thickness
                            'Thickness (mm)',
                            selectedThicknessLabel ?? '',
                            thicknessOptions
                                .map((e) => e['label'].toString())
                                .toList(),
                            (val) => setState(() {
                              selectedThicknessLabel = val;
                              selectedThicknessId =
                                  thicknessOptions.firstWhere(
                                    (e) => e['label'].toString() == val,
                                  )['id'];
                            }),
                          ),
                        ),
                        const SizedBox(height: 12), //space between dropdowns
                        //dropdown for width
                        Expanded(
                          child: buildDropdown(
                            // width
                            'Width (mm)',
                            selectedWidthLabel ?? '',
                            widthOptions
                                .map((e) => e['label'].toString())
                                .toList(),
                            (val) => setState(() {
                              selectedWidthLabel = val;
                              selectedWidthId =
                                  widthOptions.firstWhere(
                                    (e) => e['label'].toString() == val,
                                  )['id'];
                            }),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    Builder(
                      builder: (context) {
                        double? sqm = calculateSqmPerTon(
                          widthMm:
                              double.tryParse(selectedWidthLabel ?? '') ?? 0,
                          thicknessMm:
                              double.tryParse(selectedThicknessLabel ?? '') ??
                              0,
                        );
                        return RichText(
                          text: TextSpan(
                            style: DefaultTextStyle.of(context).style.copyWith(
                              fontSize: 16,
                              fontWeight: FontWeight.w600,
                            ),
                            children: [
                              const TextSpan(text: 'Square meters per ton:'),
                              TextSpan(
                                text:
                                    sqm != null
                                        ? '${sqm.toStringAsFixed(2)} m²'
                                        : '--',
                                style:  TextStyle(
                                  fontWeight: FontWeight.w900,
                                  fontSize: 18,
                                  color: Colors.blueAccent,
                                ),
                              ),
                            ],
                          ),
                        );
                      },
                    ),
                  ], //children
                ),
              ),
    );
  }
}
