import pandas as pd
from datetime import datetime

# Function to convert 12-hour time to 24-hour format
def convert_to_24hr(time_str):
    try:
        # Parse 12-hour time with AM/PM
        dt = datetime.strptime(time_str, '%I:%M %p')
        # Convert to 24-hour format
        return dt.strftime('%H:%M:%S')
    except ValueError:
        return time_str  # Return unchanged if format is unexpected

# Function to extract room for the given day
def extract_room(row):
    room_str = row['Room']
    day = row['Day']
    if ';' in room_str:
        # Split by semicolon and find the room for the given day
        for part in room_str.split(';'):
            part = part.strip()
            if part.startswith(day[:3].upper()):  # Match first 3 letters of day
                return part.split(':')[1].strip()
    return room_str  # Return as-is if no parsing needed

# Read CSV
df = pd.read_csv('class_schedule.csv')

# Convert times to 24-hour format
df['Start Time'] = df['Start Time'].apply(convert_to_24hr)
df['End Time'] = df['End Time'].apply(convert_to_24hr)

# Handle empty Faculty
df['Faculty'] = df['Faculty'].replace('', None)

# Standardize Day column (optional, ensure consistent casing)
df['Day'] = df['Day'].str.upper()

# Optionally parse Room column
df['Room'] = df.apply(extract_room, axis=1)

# Save processed CSV
df.to_csv('class_schedule_processed.csv', index=False)
print("Processed CSV saved as 'class_schedule_processed.csv'")