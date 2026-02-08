"""
API Testing Examples for Diet & Workout Recommendation System

This script demonstrates how to test the API with different user profiles
"""

import requests
import json

API_URL = "http://127.0.0.1:5000/recommend"

# Test Cases with different user profiles
test_cases = [
    {
        "name": "Test 1: Young Female, Weight Loss, Vegetarian",
        "data": {
            "age": 25,
            "gender": "Female",
            "bmi": 24.0,
            "goal": "weight_loss",
            "veg": 1,
            "disease": "none",
            "weight": 65,
            "height": 165
        }
    },
    {
        "name": "Test 2: Middle-aged Male, Weight Gain, Diabetic",
        "data": {
            "age": 40,
            "gender": "Male",
            "bmi": 27.5,
            "goal": "weight_gain",
            "veg": 0,
            "disease": "diabetes",
            "weight": 85,
            "height": 175
        }
    },
    {
        "name": "Test 3: Older Female, Maintenance, Heart Disease",
        "data": {
            "age": 55,
            "gender": "Female",
            "bmi": 28.0,
            "goal": "maintain",
            "veg": 1,
            "disease": "heart_disease",
            "weight": 75,
            "height": 163
        }
    },
    {
        "name": "Test 4: Young Male, Weight Loss, High BP",
        "data": {
            "age": 30,
            "gender": "Male",
            "bmi": 26.0,
            "goal": "weight_loss",
            "veg": 0,
            "disease": "bp",
            "weight": 80,
            "height": 175
        }
    },
    {
        "name": "Test 5: Athlete Female, Maintenance, Joint Problems",
        "data": {
            "age": 28,
            "gender": "Female",
            "bmi": 22.0,
            "goal": "maintain",
            "veg": 1,
            "disease": "joint_problems",
            "weight": 62,
            "height": 167
        }
    }
]

def test_api():
    """Test the API with all test cases"""
    
    print("=" * 100)
    print("DIET & WORKOUT RECOMMENDATION API - TESTING")
    print("=" * 100)
    print(f"\nTesting endpoint: {API_URL}\n")
    
    for i, test_case in enumerate(test_cases, 1):
        print(f"\n{'='*100}")
        print(f"{test_case['name']}")
        print(f"{'='*100}")
        
        # Show input
        print("\nRequest Data:")
        for key, value in test_case['data'].items():
            print(f"  {key:20} : {value}")
        
        try:
            # Make API request
            response = requests.post(
                API_URL,
                json=test_case['data'],
                headers={'Content-Type': 'application/json'}
            )
            
            if response.status_code == 200:
                result = response.json()
                
                print("\n✅ Response Received (200 OK)")
                print(f"\n--- Health Metrics ---")
                print(f"BMI: {result['bmi']} ({result['bmi_category']})")
                print(f"Daily Calorie Target: {result['daily_calories']} calories")
                print(f"Workout Intensity: {result['workout_intensity']}")
                
                print(f"\n--- Meal Plan (3 meals totaling {sum([m['total_calories'] for m in result['diet_plan']])} cal) ---")
                for meal in result['diet_plan']:
                    print(f"\n{meal['meal']} ({meal['total_calories']} cal):")
                    for food in meal['foods']:
                        print(f"  • {food['food_name']}: {food['calories']}cal | "
                              f"P:{food['protein']}g C:{food['carbs']}g F:{food['fats']}g")
                
                print(f"\n--- Weekly Workout Plan (Target: {result['workout_plan']['target_weekly_burn']} cal burn) ---")
                for workout in result['workout_plan']['workouts']:
                    print(f"  • {workout['workout']}: {workout['duration']} min | "
                          f"{workout['calories_burned']} cal | {workout['difficulty']}")
                
            else:
                print(f"\n❌ Error {response.status_code}")
                print(f"Response: {response.text}")
                
        except Exception as e:
            print(f"\n❌ Connection Error: {str(e)}")
            print("Make sure Flask app is running: python app.py")

def test_edge_cases():
    """Test edge cases and boundary conditions"""
    
    print(f"\n\n{'='*100}")
    print("EDGE CASE TESTING")
    print(f"{'='*100}\n")
    
    edge_cases = [
        {
            "name": "Very Low BMI (Underweight)",
            "data": {
                "age": 20, "gender": "Female", "bmi": 17.0,
                "goal": "weight_gain", "veg": 1, "disease": "none",
                "weight": 50, "height": 172
            }
        },
        {
            "name": "Very High BMI (Obese)",
            "data": {
                "age": 50, "gender": "Male", "bmi": 35.0,
                "goal": "weight_loss", "veg": 0, "disease": "diabetes",
                "weight": 105, "height": 173
            }
        },
        {
            "name": "Extreme Age (Young)",
            "data": {
                "age": 18, "gender": "Female", "bmi": 21.0,
                "goal": "weight_gain", "veg": 1, "disease": "none",
                "weight": 65, "height": 175
            }
        },
        {
            "name": "Extreme Age (Elderly)",
            "data": {
                "age": 75, "gender": "Male", "bmi": 25.0,
                "goal": "maintain", "veg": 0, "disease": "heart_disease",
                "weight": 75, "height": 173
            }
        }
    ]
    
    for test_case in edge_cases:
        print(f"\n{test_case['name']}...")
        try:
            response = requests.post(API_URL, json=test_case['data'])
            if response.status_code == 200:
                result = response.json()
                print(f"  ✅ Success - BMI: {result['bmi']}, Calories: {result['daily_calories']}, "
                      f"Intensity: {result['workout_intensity']}")
            else:
                print(f"  ❌ Failed - Status: {response.status_code}")
        except Exception as e:
            print(f"  ❌ Error: {str(e)}")

if __name__ == "__main__":
    print("\n⚠️  Make sure Flask app is running: python app.py\n")
    print("Press Enter to start testing...")
    input()
    
    test_api()
    test_edge_cases()
    
    print(f"\n\n{'='*100}")
    print("TESTING COMPLETE")
    print(f"{'='*100}\n")
