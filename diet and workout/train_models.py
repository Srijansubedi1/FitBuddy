import pandas as pd
import pickle
from sklearn.ensemble import RandomForestRegressor, RandomForestClassifier
from sklearn.preprocessing import LabelEncoder
from sklearn.model_selection import train_test_split
from sklearn.metrics import r2_score, accuracy_score, mean_squared_error
import numpy as np
import os

# ================ LOAD DATA ================
df = pd.read_csv("diet.csv")

# ================ PREPROCESSING ================
# Encode categorical features
le_gender = LabelEncoder()
le_goal = LabelEncoder()
le_disease = LabelEncoder()

df['gender_encoded'] = le_gender.fit_transform(df['gender'])
df['goal_encoded'] = le_goal.fit_transform(df['goal'])
df['disease_encoded'] = le_disease.fit_transform(df['disease'])

# Encode workout intensity
le_intensity = LabelEncoder()
df['intensity_encoded'] = le_intensity.fit_transform(df['workout_intensity'])

# Features and targets
X = df[['age', 'gender_encoded', 'bmi', 'goal_encoded', 'disease_encoded']]
y_calories = df['calories_needed']
y_intensity = df['workout_intensity']

# ================ MODEL 1: CALORIE PREDICTION ================
print("="*50)
print("Training Calorie Prediction Model...")
print("="*50)

X_train_cal, X_test_cal, y_train_cal, y_test_cal = train_test_split(
    X, y_calories, test_size=0.2, random_state=42
)

calorie_model = RandomForestRegressor(
    n_estimators=150,
    max_depth=12,
    min_samples_split=2,
    min_samples_leaf=1,
    random_state=42,
    n_jobs=-1
)

calorie_model.fit(X_train_cal, y_train_cal)

# Predictions and evaluation
y_pred_cal = calorie_model.predict(X_test_cal)
r2_cal = r2_score(y_test_cal, y_pred_cal)
rmse_cal = np.sqrt(mean_squared_error(y_test_cal, y_pred_cal))

print(f"Calorie Model R² Score: {r2_cal:.4f}")
print(f"Calorie Model RMSE: {rmse_cal:.2f}")
print(f"Sample Predictions vs Actual:")
for i in range(min(5, len(y_test_cal))):
    print(f"  Predicted: {y_pred_cal[i]:.0f}, Actual: {y_test_cal.iloc[i]}")

# ================ MODEL 2: INTENSITY CLASSIFICATION ================
print("\n" + "="*50)
print("Training Workout Intensity Classification Model...")
print("="*50)

X_train_int, X_test_int, y_train_int, y_test_int = train_test_split(
    X, y_intensity, test_size=0.2, random_state=42
)

intensity_model = RandomForestClassifier(
    n_estimators=150,
    max_depth=12,
    min_samples_split=2,
    min_samples_leaf=1,
    random_state=42,
    n_jobs=-1
)

intensity_model.fit(X_train_int, y_train_int)

# Predictions and evaluation
y_pred_int = intensity_model.predict(X_test_int)
accuracy_int = accuracy_score(y_test_int, y_pred_int)

print(f"Intensity Model Accuracy: {accuracy_int:.4f} ({accuracy_int*100:.2f}%)")
print(f"Classes: {intensity_model.classes_}")
print(f"Class Distribution in Training Data:")
print(y_train_int.value_counts())

# ================ SAVE MODELS ================
print("\n" + "="*50)
print("Saving Models...")
print("="*50)

if not os.path.exists('models'):
    os.makedirs('models')

pickle.dump(calorie_model, open("models/calorie_model.pkl", "wb"))
pickle.dump(intensity_model, open("models/intensity_model.pkl", "wb"))
pickle.dump(le_gender, open("models/le_gender.pkl", "wb"))
pickle.dump(le_goal, open("models/le_goal.pkl", "wb"))
pickle.dump(le_disease, open("models/le_disease.pkl", "wb"))
pickle.dump(le_intensity, open("models/le_intensity.pkl", "wb"))

print("✓ Models saved successfully!")
print(f"  - Calorie Model: models/calorie_model.pkl")
print(f"  - Intensity Model: models/intensity_model.pkl")
print(f"  - Label Encoders saved")

# ================ MODEL PERFORMANCE SUMMARY ================
print("\n" + "="*50)
print("FINAL MODEL PERFORMANCE")
print("="*50)
print(f"Calorie Prediction Model:")
print(f"  R² Score: {r2_cal:.4f}")
print(f"  RMSE: {rmse_cal:.2f} calories")
print(f"  Training Samples: {len(y_train_cal)}")
print(f"  Testing Samples: {len(y_test_cal)}")

print(f"\nIntensity Classification Model:")
print(f"  Accuracy: {accuracy_int:.4f} ({accuracy_int*100:.2f}%)")
print(f"  Classes: {list(intensity_model.classes_)}")
print(f"  Training Samples: {len(y_train_int)}")
print(f"  Testing Samples: {len(y_test_int)}")

print(f"\nFeatures Used:")
print(f"  1. Age")
print(f"  2. Gender (encoded)")
print(f"  3. BMI")
print(f"  4. Goal (encoded)")
print(f"  5. Disease (encoded)")

print(f"\nLabel Encodings:")
print(f"  Genders: {dict(zip(le_gender.classes_, le_gender.transform(le_gender.classes_)))}")
print(f"  Goals: {dict(zip(le_goal.classes_, le_goal.transform(le_goal.classes_)))}")
print(f"  Diseases: {dict(zip(le_disease.classes_, le_disease.transform(le_disease.classes_)))}")
print(f"  Intensities: {dict(zip(le_intensity.classes_, le_intensity.transform(le_intensity.classes_)))}")

print("\n✓ Training Complete!")
