using System;
using System.Collections.Generic;
using System.Text;
using FreightRateWSSample.FreightRateWebReference;
using System.ServiceModel;

namespace FreightRateWSSample
{
    class FreightRateWSClient
    {
        static void Main()
        {
            try
            {
                FreightRateService freightRateService = new FreightRateService();
                FreightRateRequest freightRateRequest = new FreightRateRequest();
                RequestType request = new RequestType();
                String[] requestOption = { "RateChecking Option" };
                request.RequestOption = requestOption;
                freightRateRequest.Request = request;

                /** ****************ShipFrom******************************* */
                ShipFromType shipFrom = new ShipFromType();
                AddressType shipFromAddress = new AddressType();
                String[] shipFromAddressLines = { "ShipFrom address" };
                shipFromAddress.AddressLine = shipFromAddressLines;
                shipFromAddress.City = "ShipFrom city";
                shipFromAddress.StateProvinceCode = "ShipFrom state province code";
                shipFromAddress.PostalCode = "ShipFrom postal code";
                shipFromAddress.CountryCode = "ShipFrom country code";
                shipFrom.Address = shipFromAddress;
                shipFrom.AttentionName = "ShipFrom attention name";
                shipFrom.Name = "ShipFrom Name";
                freightRateRequest.ShipFrom = shipFrom;
                /** ****************ShipFrom******************************* */

                /** ****************ShipTo*************************************** */
                ShipToType shipTo = new ShipToType();
                AddressType shipToAddress = new AddressType();
                String[] shipToAddressLines = { "ShipTo address line" };
                shipToAddress.AddressLine = shipToAddressLines;
                shipToAddress.City = "ShipTo city";
                shipToAddress.StateProvinceCode = "ShipTo state province code";
                shipToAddress.PostalCode = "ShipTo postal code";
                shipToAddress.CountryCode = "ShipTo country code";
                shipTo.Address = shipToAddress;
                shipTo.AttentionName = "ShipTo attention name";
                shipTo.Name = "ShipTo Name";
                freightRateRequest.ShipTo = shipTo;
                /** ****************ShipTo*************************************** */

                /** ***************PaymentInformationType************************* */
                PaymentInformationType paymentInfo = new PaymentInformationType();
                PayerType payer = new PayerType();
                payer.AttentionName = "Payer attention name";
                payer.Name = "Payer name";
                payer.ShipperNumber = "Payer shipper number";
                AddressType payerAddress = new AddressType();
                String[] payerAddressLines = { "Payer address line" };
                payerAddress.AddressLine = payerAddressLines;
                payerAddress.City = "Payer city";
                payerAddress.StateProvinceCode = "Payer state province code";
                payerAddress.PostalCode = "Payer postal code";
                payerAddress.CountryCode = "Payer country code";
                payer.Address = payerAddress;
                paymentInfo.Payer = payer;
                RateCodeDescriptionType shipBillOption = new RateCodeDescriptionType();
                shipBillOption.Code = "Ship bill option";
                shipBillOption.Description = "Ship bill description";
                paymentInfo.ShipmentBillingOption = shipBillOption;
                freightRateRequest.PaymentInformation = paymentInfo;
                /** ***************PaymentInformationType************************* */

                //Below code use dummy data for referenced. Please update as required


                /** ***************Service************************************** */
                RateCodeDescriptionType service = new RateCodeDescriptionType();
                service.Code = "309";
                service.Description = "UPS Ground Freight";
                freightRateRequest.Service = service;
                /** ***************Service************************************** */
                              

                /** **************Commodity************************************* */
                CommodityType commodity = new CommodityType();
                CommodityValueType commValue = new CommodityValueType();
                commValue.CurrencyCode = "USD";
                commValue.MonetaryValue = "5670";
                commodity.CommodityValue = commValue;
                commodity.NumberOfPieces = "20";
                
                RateCodeDescriptionType packagingType = new RateCodeDescriptionType();
                packagingType.Code = "BAG";
                packagingType.Description = "BAG";
                commodity.PackagingType = packagingType;
                WeightType weight = new WeightType();
                UnitOfMeasurementType unitOfMeasurement = new UnitOfMeasurementType();
                unitOfMeasurement.Code = "LBS";
                unitOfMeasurement.Description = "Pounds";
                weight.UnitOfMeasurement = unitOfMeasurement;
                weight.Value = "200";
                commodity.Weight = weight;
                commodity.Description = "LCD TVS";
                
                CommodityValueType commodityValue = new CommodityValueType();
                commodityValue.CurrencyCode = "USD";
                commodityValue.MonetaryValue = "100";
                commodity.CommodityValue = commodityValue;
                commodity.Description = "LCD TVS";
                commodity.FreightClass = "60";
                CommodityType[] commodityArray = { commodity };
                freightRateRequest.Commodity = commodityArray;
                /** **************Commodity************************************* */


                /** **************HandlingUnitOne************************************* */
                HandlingUnitType handUnitType = new HandlingUnitType();
                handUnitType.Quantity = "1";
                RateCodeDescriptionType rateCodeDescType = new RateCodeDescriptionType();
                rateCodeDescType.Code = "SKD";
                rateCodeDescType.Description = "SKID";
                handUnitType.Type = rateCodeDescType;
                freightRateRequest.HandlingUnitOne = handUnitType;

                /** **************HandlingUnitOne************************************* */ 
                

                UPSSecurity upss = new UPSSecurity();
                UPSSecurityServiceAccessToken upsSvcToken = new UPSSecurityServiceAccessToken();
                upsSvcToken.AccessLicenseNumber = "Your License Number";
                upss.ServiceAccessToken = upsSvcToken;
                UPSSecurityUsernameToken upsSecUsrnameToken = new UPSSecurityUsernameToken();
                upsSecUsrnameToken.Username = "Your Username";
                upsSecUsrnameToken.Password = "Your Password";
                upss.UsernameToken = upsSecUsrnameToken;
                freightRateService.UPSSecurityValue = upss;

                System.Net.ServicePointManager.CertificatePolicy = new TrustAllCertificatePolicy();
                Console.WriteLine(freightRateRequest);
                FreightRateResponse freightRateResponse = freightRateService.ProcessFreightRate(freightRateRequest);
                Console.WriteLine("Response code: " + freightRateResponse.Response.ResponseStatus.Code);
                Console.WriteLine("Response description: " + freightRateResponse.Response.ResponseStatus.Description);
                Console.ReadKey();
            }
            catch (System.Web.Services.Protocols.SoapException ex)
            {
                Console.WriteLine("");
                Console.WriteLine("---------Freight Rate Web Service returns error----------------");
                Console.WriteLine("---------\"Hard\" is user error \"Transient\" is system error----------------");
                Console.WriteLine("SoapException Message= " + ex.Message);
                Console.WriteLine("");
                Console.WriteLine("SoapException Category:Code:Message= " + ex.Detail.LastChild.InnerText);
                Console.WriteLine("");
                Console.WriteLine("SoapException XML String for all= " + ex.Detail.LastChild.OuterXml);
                Console.WriteLine("");
                Console.WriteLine("SoapException StackTrace= " + ex.StackTrace);
                Console.WriteLine("-------------------------");
                Console.WriteLine("");
            }    
            catch (System.ServiceModel.CommunicationException ex)
            {
                Console.WriteLine("");
                Console.WriteLine("--------------------");
                Console.WriteLine("CommunicationException= " + ex.Message);
                Console.WriteLine("CommunicationException-StackTrace= " + ex.StackTrace);
                Console.WriteLine("-------------------------");
                Console.WriteLine("");

            }
            catch (Exception ex)
            {
                Console.WriteLine("");
                Console.WriteLine("-------------------------");
                Console.WriteLine(" General Exception= " + ex.Message);
                Console.WriteLine(" General Exception-StackTrace= " + ex.StackTrace);
                Console.WriteLine("-------------------------");
 
            }
            finally
            {
                Console.ReadKey();
            }

        }
    }
}
