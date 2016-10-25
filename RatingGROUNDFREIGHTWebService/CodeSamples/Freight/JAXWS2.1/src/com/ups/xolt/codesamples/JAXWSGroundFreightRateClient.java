/* 
 ** 
 ** Filename: JAXWSGroundFreightRateClient 
 ** Authors: United Parcel Service of America
 ** 
 ** The use, disclosure, reproduction, modification, transfer, or transmittal 
 ** of this work for any purpose in any form or by any means without the 
 ** written permission of United Parcel Service is strictly prohibited. 
 ** 
 ** Confidential, Unpublished Property of United Parcel Service. 
 ** Use and Distribution Limited Solely to Authorized Personnel. 
 ** 
 ** Copyright 2009 United Parcel Service of America, Inc.  All Rights Reserved. 
 ** 
 */
package com.ups.xolt.codesamples;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.util.Calendar;
import java.util.List;
import java.util.Properties;

import javax.xml.ws.BindingProvider;

import com.ups.wsdl.xoltws.freightrate.v1.FreightRatePortType;
import com.ups.wsdl.xoltws.freightrate.v1.FreightRateService;
import com.ups.wsdl.xoltws.freightrate.v1.RateErrorMessage;
import com.ups.xmlschema.xoltws.common.v1.RequestType;
import com.ups.xmlschema.xoltws.error.v1.ErrorDetailType;
import com.ups.xmlschema.xoltws.freightrate.v1.AddressType;
import com.ups.xmlschema.xoltws.freightrate.v1.CommodityType;
import com.ups.xmlschema.xoltws.freightrate.v1.CommodityValueType;
import com.ups.xmlschema.xoltws.freightrate.v1.FreightRateRequest;
import com.ups.xmlschema.xoltws.freightrate.v1.FreightRateResponse;
import com.ups.xmlschema.xoltws.freightrate.v1.HandlingUnitType;
import com.ups.xmlschema.xoltws.freightrate.v1.PayerType;
import com.ups.xmlschema.xoltws.freightrate.v1.PaymentInformationType;
import com.ups.xmlschema.xoltws.freightrate.v1.RateCodeDescriptionType;
import com.ups.xmlschema.xoltws.freightrate.v1.ShipFromType;
import com.ups.xmlschema.xoltws.freightrate.v1.ShipToType;
import com.ups.xmlschema.xoltws.freightrate.v1.UnitOfMeasurementType;
import com.ups.xmlschema.xoltws.freightrate.v1.WeightType;
import com.ups.xmlschema.xoltws.upss.v1.UPSSecurity;
public class JAXWSGroundFreightRateClient {    
    static FreightRateService service;
    
    private static final String LICENSE_NUMBER = "accesskey";
	private static final String USER_NAME = "username";
	private static final String PASSWORD = "password";
	private static final String ENDPOINT_URL="url";
	private static final String OUT_FILE_LOCATION = "out_file_location";
	private static final String TOOL_OR_WEB_SERVICE_NAME = "tool_or_webservice_name";
    private static Properties props = null;
    private static String statusCode = null;
	private static String description = null;
    static {
    	props = new Properties();
    	try{
    		props.load(new FileInputStream("./build.properties"));
    	}catch (Exception e) {
			statusCode = e.getMessage();
			description = e.toString();
			updateResultsToFile(statusCode, description);
    		e.printStackTrace();
		}	
    }    
    
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
    	try {
        	service = new FreightRateService();
        	FreightRatePortType freightRatePortType = service.getFreightRatePort();
        	
        	BindingProvider bp = (BindingProvider)freightRatePortType;
        	bp.getRequestContext().put(BindingProvider.ENDPOINT_ADDRESS_PROPERTY, props.getProperty(ENDPOINT_URL));
           
        	FreightRateResponse freightRateResponse = freightRatePortType.processFreightRate(populateGroundFreightRateRequest(), populateUPSSecurity());
            statusCode= freightRateResponse.getResponse().getResponseStatus().getCode();
            description = freightRateResponse.getResponse().getResponseStatus().getDescription();
            updateResultsToFile(statusCode, description);
            
        } catch(Exception e) {
        	if(e instanceof RateErrorMessage){
        		RateErrorMessage rateErrMsg = ((RateErrorMessage)e);
        		List errorDetail = rateErrMsg.getFaultInfo().getErrorDetail();
        		ErrorDetailType errorDetailType = (ErrorDetailType) errorDetail.get(0);
        		statusCode = errorDetailType.getPrimaryErrorCode().getCode();
        		description = errorDetailType.getPrimaryErrorCode().getDescription(); 
        		updateResultsToFile(statusCode, description);
        	} else{
        		statusCode = e.getMessage();
        		description = e.toString();
        		updateResultsToFile(statusCode, description);
        	}
            e.printStackTrace();
        }
    }
    
    private static FreightRateRequest populateGroundFreightRateRequest(){
    	FreightRateRequest freightRateRequest = new FreightRateRequest();
		RequestType request = new RequestType();
		//String[] requestOption = { "1" };
		//request.setRequestOption(requestOption);
		List requestOption = request.getRequestOption();
		requestOption.add("1");
		freightRateRequest.setRequest(request);

		/** ****************ShipFrom******************************* */
		ShipFromType shipFrom = new ShipFromType();
		AddressType shipFromAddress = new AddressType();
		//String[] shipFromAddressLines = { "AddressLine1" };
		//shipFromAddress.setAddressLine(shipFromAddressLines);
		
		List shipFromAddressLines = shipFromAddress.getAddressLine();
		shipFromAddressLines.add("AddressLine1");
		
		shipFromAddress.setCity("Roswell");
		shipFromAddress.setStateProvinceCode("GA");
		shipFromAddress.setPostalCode("30076");
		shipFromAddress.setCountryCode("US");
		shipFrom.setAddress(shipFromAddress);
		shipFrom.setAttentionName("Mr. XYZ");
		shipFrom.setName("XYZ Associates");
		freightRateRequest.setShipFrom(shipFrom);
		/** ****************ShipFrom******************************* */

		/** ****************ShipTo*************************************** */
		ShipToType shipTo = new ShipToType();
		AddressType shipToAddress = new AddressType();
		//String[] shipToAddressLines = { "123 main st", "Address Line2","Address Line3" };
		//shipToAddress.setAddressLine(shipToAddressLines);
		List shipToAddressLines = shipToAddress.getAddressLine();
		shipToAddressLines.add("123 main st");
		shipToAddressLines.add("Address Line2");
		shipToAddressLines.add("Address Line3");
		
		shipToAddress.setCity("Timonium");
		shipToAddress.setStateProvinceCode("MD");
		shipToAddress.setPostalCode("21093");
		shipToAddress.setCountryCode("US");
		shipTo.setAddress(shipFromAddress);
		shipTo.setAttentionName("PQR Associates");
		shipTo.setName("Mr. PQR");
		freightRateRequest.setShipTo(shipTo);
		/** ****************ShipTo*************************************** */

		/** ***************PaymentInformationType************************* */
		PaymentInformationType paymentInfo = new PaymentInformationType();
		PayerType payer = new PayerType();
		payer.setAttentionName("Mr. XYZ");
		payer.setName("XYZ Associates");
		payer.setShipperNumber("GG0111");
		AddressType payerAddress = new AddressType();
		//String[] payerAddressLines = { "123 main st", "Address Line2","Address Line3" };
		//payerAddress.setAddressLine(payerAddressLines);
		List payerAddressLines = payerAddress.getAddressLine();
		payerAddressLines.add("123 main st");
		payerAddressLines.add("Address Line2");
		payerAddressLines.add("123 main st");
		payerAddress.setCity("Chicago");
		payerAddress.setStateProvinceCode("IL");
		payerAddress.setPostalCode("60614");
		payerAddress.setCountryCode("US");
		payer.setAddress(payerAddress);
		paymentInfo.setPayer(payer);
		RateCodeDescriptionType shipBillOption = new RateCodeDescriptionType();
		shipBillOption.setCode("10");
		shipBillOption.setDescription("PREPAID");
		paymentInfo.setShipmentBillingOption(shipBillOption);
		freightRateRequest.setPaymentInformation(paymentInfo);
		/** ***************PaymentInformationType************************* */

		/** ***************Service************************************** */
		RateCodeDescriptionType service = new RateCodeDescriptionType();
		service.setCode("309");
		service.setDescription("UPS Ground Freight");
		freightRateRequest.setService(service);
		/** ***************Service************************************** */

		/** **************Commodity************************************* */
		CommodityType commodity = new CommodityType();
		commodity.setNumberOfPieces("20");
		//NMFCCommodityType nmfcCommodity = new NMFCCommodityType();
		//nmfcCommodity.setPrimeCode("132680");
		//nmfcCommodity.setSubCode("02");
		//commodity.setNMFCCommodity(nmfcCommodity);
		commodity.setFreightClass("77.5");
		RateCodeDescriptionType packagingType = new RateCodeDescriptionType();
		packagingType.setCode("BAG");
		packagingType.setDescription("BAG");
		commodity.setPackagingType(packagingType);
		WeightType weight = new WeightType();
		weight.setValue("200");
		UnitOfMeasurementType unitOfMeasurement = new UnitOfMeasurementType();
		unitOfMeasurement.setCode("lbs");
		unitOfMeasurement.setDescription("pounds");
		weight.setUnitOfMeasurement(unitOfMeasurement);
		commodity.setWeight(weight);
		CommodityValueType commodityValue = new CommodityValueType();
		commodityValue.setCurrencyCode("USD");
		commodityValue.setMonetaryValue("100");
		commodity.setCommodityValue(commodityValue);
		commodity.setDescription("LCD TVS");
		//CommodityType[] commodityArray = { commodity };
		//freightRateRequest.setCommodity(commodityArray);
		
		List commodityList = freightRateRequest.getCommodity();
		commodityList.add(commodity);
		/** **************Commodity************************************* */

		/** **************HandlingUnitOne************************** */
		HandlingUnitType handlingUnit = new HandlingUnitType();
		handlingUnit.setQuantity("1");
		RateCodeDescriptionType handlingUnitType = new RateCodeDescriptionType();
		handlingUnitType.setCode("SKD");
		handlingUnitType.setDescription("SKID");
		handlingUnit.setType(handlingUnitType);
		freightRateRequest.setHandlingUnitOne(handlingUnit);
		/** **************HandlingUnitOne************************** */
    	return freightRateRequest;
    }
    
    private static UPSSecurity populateUPSSecurity(){
    	UPSSecurity upss = new UPSSecurity();
		UPSSecurity.ServiceAccessToken upsSvcToken = new UPSSecurity.ServiceAccessToken();
		upsSvcToken.setAccessLicenseNumber(props.getProperty(LICENSE_NUMBER));
		upss.setServiceAccessToken(upsSvcToken);
		UPSSecurity.UsernameToken upsSecUsrnameToken = new UPSSecurity.UsernameToken();
		upsSecUsrnameToken.setUsername(props.getProperty(USER_NAME));
		upsSecUsrnameToken.setPassword(props.getProperty(PASSWORD));
		upss.setUsernameToken(upsSecUsrnameToken);
		
		return upss;		
    }
    
    /**
     * This method updates the XOLTResult.xml file with the received status and description
     * @param statusCode
     * @param description
     */
	   private static void updateResultsToFile(String statusCode, String description){
	    	BufferedWriter bw = null;
	    	try{    		
	    		
	    		File outFile = new File(props.getProperty(OUT_FILE_LOCATION));
	    		System.out.println("Output file deletion status: " + outFile.delete());
	    		outFile.createNewFile();
	    		System.out.println("Output file location: " + outFile.getCanonicalPath());
	    		bw = new BufferedWriter(new FileWriter(outFile));
	    		StringBuffer strBuf = new StringBuffer();
	    		strBuf.append("<ExecutionAt>");
	    		strBuf.append(Calendar.getInstance().getTime());
	    		strBuf.append("</ExecutionAt>\n");
	    		strBuf.append("<ToolOrWebServiceName>");
	    		strBuf.append(props.getProperty(TOOL_OR_WEB_SERVICE_NAME));
	    		strBuf.append("</ToolOrWebServiceName>\n");
	    		strBuf.append("\n");
	    		strBuf.append("<ResponseStatus>\n");
	    		strBuf.append("\t<Code>");
	    		strBuf.append(statusCode);
	    		strBuf.append("</Code>\n");
	    		strBuf.append("\t<Description>");
	    		strBuf.append(description);
	    		strBuf.append("</Description>\n");
	    		strBuf.append("</ResponseStatus>");
	    		bw.write(strBuf.toString());
	    		bw.close();    		    		
	    	}catch (Exception e) {
				e.printStackTrace();
			}finally{
				try{
					if (bw != null){
						bw.close();
						bw = null;
					}
				}catch (Exception e) {
					e.printStackTrace();
				}			
			}		
	    }
}
