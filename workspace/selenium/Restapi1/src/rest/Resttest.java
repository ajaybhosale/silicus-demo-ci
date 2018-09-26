package rest;


import org.testng.Assert;
import org.testng.annotations.Test;

import io.restassured.RestAssured;
import io.restassured.response.Response;
import io.restassured.response.ResponseBody;
import io.restassured.specification.RequestSpecification;

public class Resttest 
{

	@Test
	public void verify_statuscode_for_GETresponse ()
	{
		RestAssured.baseURI = "https://phpsildemo.azurewebsites.net/companies";
		RequestSpecification httprequest = RestAssured.given();
		Response response = httprequest.get(RestAssured.baseURI);
		ResponseBody body = response.getBody();
		String responsebody = body.asString();
		System.out.println("Response body is "+responsebody);
		int statuscode = response.getStatusCode();
		Assert.assertEquals(statuscode, 200,"Status Code got changed");		
	}
	
	@Test 
	public void verify_addresstypeasprimary_in_GETresponse()
	{
		RestAssured.baseURI ="https://phpsildemo.azurewebsites.net/companies/1/addresses?address_type=primary";
		RequestSpecification httprequest = RestAssured.given();
		Response response = httprequest.get(RestAssured.baseURI);
		int statuscode = response.getStatusCode();
		Assert.assertEquals(statuscode, 200, "Status code got changed");
		String responsebody = response.asString();
		Assert.assertEquals(responsebody.contains("\"address_type\":\"primary\""), true , "Primary Addresstype is not present");
	}
	
	@Test
	public void verify_addresstypassecondary_in_GETresponse()
	{
	RestAssured.baseURI ="https://phpsildemo.azurewebsites.net/companies/1/addresses?address_type=secondary";
	RequestSpecification httprequest = RestAssured.given();
	Response response = httprequest.get(RestAssured.baseURI);
	int statuscode = response.getStatusCode();
	Assert.assertEquals(statuscode, 200, "Status code got changed");
	String responsebody = response.asString();
	Assert.assertEquals(responsebody.contains("\"address_type\":\"secondary\""), true , "Secondary Addresstype is not present");
		
	}
	

}

