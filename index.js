const express = require("express");
const axios = require("axios");
const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

app.post("/update", async (req, res) => {
  const { imei, latitude, longitude, fall, sos } = req.body;

  if (!imei || !latitude || !longitude) {
    return res.status(400).json({ error: "Missing required fields" });
  }

  const data = {
    latitude,
    longitude,
    fall,
    sos,
    timestamp: new Date().toISOString()
  };

  try {
    const firebaseURL = `https://tracking-device-c1d82-default-rtdb.firebaseio.com/devices/${imei}.json`;
    const response = await axios.put(firebaseURL, data);
    res.status(200).json({ success: true, firebase: response.data });
  } catch (error) {
    res.status(500).json({ error: "Failed to update Firebase", details: error.message });
  }
});

app.get("/", (req, res) => {
  res.send("Arduino-Firebase Middleware Active");
});

app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
