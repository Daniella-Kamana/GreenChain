{-# LANGUAGE DataKinds #-}
{-# LANGUAGE NoImplicitPrelude #-}
{-# LANGUAGE TemplateHaskell #-}
{-# LANGUAGE ScopedTypeVariables #-}
{-# LANGUAGE MultiParamTypeClasses #-}
{-# LANGUAGE OverloadedStrings #-}

module Validator where

import PlutusTx
import PlutusTx.Prelude
import Ledger
import Ledger.Contexts
import Prelude (IO)

-------------------------------------------------
-- DATUM
-------------------------------------------------

-- Datum stores the owner's public key hash
newtype OwnerDatum = OwnerDatum
  { ownerPkh :: PubKeyHash
  }

PlutusTx.unstableMakeIsData ''OwnerDatum

-------------------------------------------------
-- VALIDATOR LOGIC
-------------------------------------------------

{-# INLINABLE mkValidator #-}
mkValidator :: OwnerDatum -> () -> ScriptContext -> Bool
mkValidator datum _ ctx =
    traceIfFalse "Owner signature missing" signedByOwner
  where
    info :: TxInfo
    info = scriptContextTxInfo ctx

    signedByOwner :: Bool
    signedByOwner = txSignedBy info (ownerPkh datum)

-------------------------------------------------
-- COMPILE VALIDATOR
-------------------------------------------------

validator :: Validator
validator = mkValidatorScript
  $$(PlutusTx.compile [|| mkValidator ||])

validatorHash :: ValidatorHash
validatorHash = validatorHash validator

scriptAddress :: Address
scriptAddress = scriptHashAddress validatorHash
