/* 
 ** 
 ** Filename: Axis2FreightRateClient.java 
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
import java.util.Properties;

import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.RateErrorMessage;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.AddressType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.CommodityType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.CommodityValueType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.FreightRateRequest;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.FreightRateResponse;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.HandlingUnitType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.PayerType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.PaymentInformationType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.RateCodeDescriptionType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.RequestType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.ServiceAccessToken_type0;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.ShipFromType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.ShipToType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.UPSSecurity;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.UnitOfMeasurementType;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.UsernameToken_type0;
import com.ups.www.wsdl.xoltws.freightrate.v1_0.FreightRateServiceStub.WeightType;


public class Axis2GroundFreightRateClient {

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
    
	public static void main(String[] arguments) throws Exception {
		try {
			
			FreightRateServiceStub freightRateServiceStub = new FreightRateServiceStub(props.getProperty(ENDPOINT_URL));
			FreightRateResponse freightRateResponse = freightRateServiceStub.ProcessFreightRate(populateGroundFreightRateRequest(), populateUPSSecurity());
			statusCode = freightRateResponse.getResponse().getResponseStatus().getCode();
			description = freightRateResponse.getResponse().getResponseStatus().getDescription();
			updateResultsToFile(statusCode, description);
		} catch (Exception e) {
			 if (e instanceof RateErrorMessage){
				 RateErrorMessage rateErr = (RateErrorMessage)e;
				 statusCode = rateErr.getFaultMessage().getErrorDetail()[0].getPrimaryErrorCode().getCode();
				 description = rateErr.getFaultMessage().getErrorDetail()[0].getPrimaryErrorCode().getDescription();
				updateResultsToFile(statusCode, description); 
			 } else {
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
		String[] requestOption = { "1" };
		request.setRequestOption(requestOption);
		freightRateRequest.setRequest(request);

		/** ****************ShipFrom******************************* */
		ShipFromType shipFrom = new ShipFromType();
		AddressType shipFromAddress = new AddressType();
		String[] shipFromAddressLines = { "AddressLine1" };
		shipFromAddress.setAddressLine(shipFromAddressLines);
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
		String[] shipToAddressLines = { "123 main st", "Address Line2",
				"Address Line3" };
		shipToAddress.setAddressLine(shipToAddressLines);
		shipToAddress.setCity("Timonium");
		shipToAddress.setStateProvinceCode("MD");
		shipToAddress.setPostalCode("21093");
		shipToAddress.setCountryCode("US");
		shipTo.setAddress(shipFromAddress);
		shipTo.setAttentionName("DEF Associates");
		shipTo.setName("DEF");
		freightRateRequest.setShipTo(shipTo);
		/** ****************ShipTo*************************************** */

		/** ***************PaymentInformationType************************* */
		PaymentInformationType paymentInfo = new PaymentInformationType();
		PayerType payer = new PayerType();
		payer.setAttentionName("Mr. XYZ");
		payer.setName("XYZ Associates");
		payer.setShipperNumber("GG0111");
		AddressType payerAddress = new AddressType();
		String[] payerAddressLines = { "123 main st", "Address Line2",
				"Address Line3" };
		payerAddress.setAddressLine(payerAddressLines);
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
        CommodityValueType commValue = new CommodityValueType();
        commValue.setCurrencyCode("USD");
        commValue.setMonetaryValue("5670");
        commodity.setCommodityValue(commValue);
		commodity.setNumberOfPieces("20");
		commodity.setFreightClass("60");
	 
		RateCodeDescriptionType packagingType = new RateCodeDescriptionType();
		packagingType.setCode("BAG");
		packagingType.setDescription("BAG");
		commodity.setPackagingType(packagingType);
		WeightType weight = new WeightType();
		UnitOfMeasurementType unitOfMeasurement = new UnitOfMeasurementType();
		unitOfMeasurement.setCode("LBS");
		unitOfMeasurement.setDescription("Pounds");
		weight.setUnitOfMeasurement(unitOfMeasurement);
		weight.setValue("750");
		commodity.setWeight(weight);
		CommodityValueType commodityValue = new CommodityValueType();
		commodityValue.setCurrencyCode("USD");
		commodityValue.setMonetaryValue("100");
		commodity.setCommodityValue(commodityValue);
		commodity.setDescription("LCD TVS");
		CommodityType[] commodityArray = { commodity };
		freightRateRequest.setCommodity(commodityArray);
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
		ServiceAccessToken_type0 upsSvcToken = new ServiceAccessToken_type0();
		upsSvcToken.setAccessLicenseNumber(props.getProperty(LICENSE_NUMBER));
		upss.setServiceAccessToken(upsSvcToken);
		UsernameToken_type0 upsSecUsrnameToken = new UsernameToken_type0();
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
